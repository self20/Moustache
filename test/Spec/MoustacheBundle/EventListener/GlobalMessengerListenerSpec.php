<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\EventListener;

use MoustacheBundle\EventListener\GlobalMessengerListener;
use MoustacheBundle\Message\CanDispatchMessage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Manager\UserManager;

class GlobalMessengerListenerSpec extends ObjectBehavior
{
    public function let(
        CanDispatchMessage $messageDispatcher,
        AuthenticatedUserHelper $authenticatedUserHelper,
        UserManager $userManager,

        GetResponseEvent $event,
        Request $request,
        AttributeBagInterface $attributeBag,
        UserInterface $authenticatedUser
    ) {
        $attributeBag->get('_route')->willReturn(GlobalMessengerListener::HOME_ROUTE);
        $request->attributes = $attributeBag;
        $request->isXmlHttpRequest()->willReturn(false);
        $event->getRequest()->willReturn($request);

        $authenticatedUser->isNew()->willReturn(true);
        $authenticatedUserHelper->getWhenAvailable()->willReturn($authenticatedUser);

        $userManager->incrementCurrentMessage($authenticatedUser)->willReturn();

        $this->beConstructedWith($messageDispatcher, $authenticatedUserHelper, $userManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GlobalMessengerListener::class);
    }

    public function it_does_nothing_if_there_is_no_authenticated_user($authenticatedUserHelper, $messageDispatcher, $event)
    {
        $authenticatedUserHelper->getWhenAvailable()->willReturn(null);

        $messageDispatcher->info(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event)->shouldReturn($event);
    }

    public function it_does_nothing_if_authenticated_user_is_not_new($authenticatedUser, $messageDispatcher, $event)
    {
        $authenticatedUser->isNew()->willReturn(false);

        $messageDispatcher->info(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event)->shouldReturn($event);
    }

    public function it_does_nothing_if_current_page_is_not_homepage($attributeBag, $messageDispatcher, $event)
    {
        $attributeBag->get('_route')->willReturn('some_route');

        $messageDispatcher->info(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event)->shouldReturn($event);
    }

    public function it_does_nothing_if_request_is_ajax($request, $messageDispatcher, $event)
    {
        $request->isXmlHttpRequest()->willReturn(true);

        $messageDispatcher->info(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event)->shouldReturn($event);
    }

    public function it_greets_user($event, $messageDispatcher)
    {
        $messageDispatcher->info(CanDispatchMessage::GREET_USER)->shouldBeCalledTimes(1);

        $this->onKernelRequest($event)->shouldReturn($event);
    }

    public function it_increments_users_current_message($userManager, $authenticatedUser, $event)
    {
        $userManager->incrementCurrentMessage($authenticatedUser)->shouldBeCalledTimes(1);

        $this->onKernelRequest($event)->shouldReturn($event);
    }
}
