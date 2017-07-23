<?php

namespace Spec\MoustacheBundle\Helper;

use MoustacheBundle\Service\FlashMessageGenerator;
use MoustacheBundle\Service\FlashMessengerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use TorrentBundle\Entity\User;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Manager\UserManager;

class FlashMessageGeneratorSpec extends ObjectBehavior
{
    public function let(
        FlashMessengerInterface $flashMessenger,
        AuthenticatedUserHelper $authenticatedUserHelper,
        UserManager $userManager,
        RequestStack $requestStack,

        Request $request,
        User $user
    ) {
        $request->isXmlHttpRequest()->willReturn(false);

        $user->isNew()->willReturn(true);
        $authenticatedUserHelper->getWhenAvailable()->willReturn($user);

        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($flashMessenger, $authenticatedUserHelper, $userManager, $requestStack);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FlashMessageGenerator::class);
    }

    public function it_ignores_ajax_requests($request, $flashMessenger)
    {
        $request->isXmlHttpRequest()->willReturn(true);

        $flashMessenger->info(Argument::any())->shouldNotBeCalled();
        $flashMessenger->warn(Argument::any())->shouldNotBeCalled();
        $flashMessenger->error(Argument::any())->shouldNotBeCalled();

        $this->warnTorrentIsMissing()->shouldReturn(null);
        $this->greetUser()->shouldReturn(null);
    }

    public function it_does_not_greet_user_if_not_authenticated($authenticatedUserHelper, $flashMessenger, $userManager)
    {
        $authenticatedUserHelper->getWhenAvailable()->willReturn(null);

        $flashMessenger->info(Argument::type('string'))->shouldNotBeCalled();
        $userManager->incrementCurrentMessage(Argument::any())->shouldNotBeCalled();

        $this->greetUser()->shouldReturn(null);
    }

    public function it_does_not_warn_if_no_user_is_authenticated($authenticatedUserHelper, $flashMessenger)
    {
        $authenticatedUserHelper->getWhenAvailable()->willReturn(null);

        $flashMessenger->warn(Argument::type('string'))->shouldNotBeCalled();

        $this->warnTorrentIsMissing()->shouldReturn(null);
    }

    public function it_does_not_greet_a_older_users($user, $flashMessenger, $userManager)
    {
        $user->isNew()->willReturn(false);

        $flashMessenger->info(Argument::type('string'))->shouldNotBeCalled();
        $userManager->incrementCurrentMessage(Argument::any())->shouldNotBeCalled();

        $this->greetUser()->shouldReturn(null);
    }

    public function it_adds_a_greet_to_flashbag_for_new_users($flashMessenger)
    {
        $flashMessenger->info(Argument::type('string'))->shouldBeCalledTimes(1);

        $this->greetUser()->shouldReturn(null);
    }

    public function it_increments_the_current_message_after_greeting($userManager)
    {
        $userManager->incrementCurrentMessage(Argument::any())->shouldBeCalledTimes(1);

        $this->greetUser()->shouldReturn(null);
    }

    public function it_warns_user_a_torrent_is_missing($flashMessenger)
    {
        $flashMessenger->warn(Argument::type('string'))->shouldBeCalledTimes(1);

        $this->warnTorrentIsMissing()->shouldReturn(null);
    }
}
