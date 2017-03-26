<?php

namespace Spec\MoustacheBundle\Helper;

use MoustacheBundle\Helper\FlashBagHelper;
use MoustacheBundle\Service\FlashMessengerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TorrentBundle\Entity\User;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Manager\UserManager;

class FlashBagHelperSpec extends ObjectBehavior
{
    public function let(
        FlashMessengerInterface $flashMessenger,
        AuthenticatedUserHelper $authenticatedUserHelper,
        UserManager $userManager,

        User $user
    ) {
        $user->isNew()->willReturn(true);
        $authenticatedUserHelper->getWhenAvailable()->willReturn($user);

        $this->beConstructedWith($flashMessenger, $authenticatedUserHelper, $userManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FlashBagHelper::class);
    }

    public function it_does_not_greet_user_if_not_authenticated($authenticatedUserHelper, $flashMessenger, $userManager)
    {
        $authenticatedUserHelper->getWhenAvailable()->willReturn(null);

        $flashMessenger->info(Argument::type('string'))->shouldNotBeCalled();
        $userManager->incrementCurrentMessage(Argument::any())->shouldNotBeCalled();

        $this->greetUser()->shouldReturn(null);
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
}
