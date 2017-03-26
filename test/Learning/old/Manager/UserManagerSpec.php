<?php

declare(strict_types=1);

namespace Spec\Test\TorrentBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use TorrentBundle\Entity\UserInterface;

class UserManagerSpec extends ObjectBehavior
{
    public function let(
        EntityManagerInterface $entityManager,

        UserInterface $user
    ) {
        $user->getCurrentMessage()->willReturn(1);

        $this->beConstructedWith($entityManager);
    }

    public function it_increments_current_message($user, $entityManager)
    {
        $user->setCurrentMessage(2)->shouldBeCalledTimes(1);
        $entityManager->persist($user)->shouldBeCalledTimes(1);

        $this->incrementCurrentMessage($user)->shouldReturn(null);
    }
}
