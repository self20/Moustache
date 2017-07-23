<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\EventListener;

use MoustacheBundle\EventListener\GlobalMessengerListener;
use MoustacheBundle\Service\FlashMessageGenerator;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class GlobalMessengerListenerSpec extends ObjectBehavior
{
    public function let(
        FlashMessageGenerator $flashMessageGenerator,

        GetResponseEvent $event,
        Request $request,
        AttributeBagInterface $attributeBag
    ) {
        $attributeBag->get('_route')->willReturn(GlobalMessengerListener::HOME_ROUTE);
        $request->attributes = $attributeBag;
        $event->getRequest()->willReturn($request);

        $this->beConstructedWith($flashMessageGenerator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GlobalMessengerListener::class);
    }

    public function it_does_nothing_if_current_page_is_not_homepage($attributeBag, $flashMessageGenerator, $event)
    {
        $attributeBag->get('_route')->willReturn('some_route');

        $flashMessageGenerator->greetUser()->shouldNotBeCalled();

        $this->onKernelRequest($event)->shouldReturn($event);
    }

    public function it_greets_user($event, $flashMessageGenerator)
    {
        $flashMessageGenerator->greetUser()->shouldBeCalledTimes(1);

        $this->onKernelRequest($event)->shouldReturn($event);
    }
}
