<?php

declare(strict_types=1);

namespace TorrentBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use TorrentBundle\Entity\UserInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param UserInterface $user
     */
    public function incrementCurrentMessage(UserInterface $user)
    {
        $user->setCurrentMessage($user->getCurrentMessage() + 1);

        $this->entityManager->persist($user);
    }
}
