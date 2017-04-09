<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Doctrine\UserManager as FosUserManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TorrentBundle\Entity\UserInterface;

class UserManagerSpec extends ObjectBehavior
{
    public function let(
        EntityManagerInterface $entityManager,
        FosUserManager $fosUserManager,

        UserInterface $user
    ) {
        $user->getCurrentMessage()->willReturn(1);

        $fosUserManager->createUser()->willReturn($user);

        $this->beConstructedWith($entityManager, $fosUserManager);
    }

    public function it_increments_current_message($user, $entityManager)
    {
        $user->setCurrentMessage(2)->shouldBeCalledTimes(1);
        $entityManager->persist($user)->shouldBeCalledTimes(1);

        $this->incrementCurrentMessage($user)->shouldReturn(null);
    }

    public function it_creates_a_new_user($user, $fosUserManager)
    {
        $user->setUsername('new')->shouldBeCalledTimes(1);
        $user->setEmail('new')->shouldBeCalledTimes(1);
        $user->setEnabled(false)->shouldBeCalledTimes(1);
        $user->setPlainPassword(Argument::type('string'))->shouldBeCalledTimes(1);
        $fosUserManager->updatePassword($user)->shouldBeCalledTimes(1);

        $this->create('new')->shouldReturn($user);
    }

    public function it_updates_a_user($user, $fosUserManager)
    {
        $fosUserManager->updateUser($user)->shouldBeCalledTimes(1);

        $this->update($user);
    }

    public function it_generates_a_confirmation_token($user, $entityManager)
    {
        $user->setConfirmationToken(Argument::type('string'))->shouldBeCalledTimes(1);
        $entityManager->persist($user)->shouldBeCalledTimes(1);

        $this->generateConfirmationToken($user);
    }

    public function it_flushes_the_entity_manager($entityManager)
    {
        $entityManager->flush()->shouldBeCalledTimes(1);

        $this->flush();
    }
}
