<?php

declare(strict_types=1);

namespace TorrentBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Doctrine\UserManager as FosUserManager;
use TorrentBundle\Entity\UserInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FosUserManager
     */
    private $fosUserManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FosUserManager         $fosUserManager
     */
    public function __construct(EntityManagerInterface $entityManager, FosUserManager $fosUserManager)
    {
        $this->entityManager = $entityManager;
        $this->fosUserManager = $fosUserManager;
    }

    /**
     * @param UserInterface $user
     */
    public function incrementCurrentMessage(UserInterface $user)
    {
        $user->setCurrentMessage($user->getCurrentMessage() + 1);

        $this->entityManager->persist($user);
    }

    /**
     * @return UserInterface
     */
    public function create(string $username): UserInterface
    {
        $user = $this->fosUserManager->createUser();
        $user->setUsername($username);
        $user->setEmail($username);
        $user->setEnabled(false);
        $user->setPlainPassword(random_bytes(20));

        $this->update($user);

        return $user;
    }

    /**
     * @param UserInterface $user
     */
    public function update(UserInterface $user)
    {
        $this->fosUserManager->updateUser($user);
    }

    /**
     * @param UserInterface $user
     */
    public function generateConfirmationToken(UserInterface $user)
    {
        $user->setConfirmationToken(hash('sha256', random_bytes(15)));

        $this->entityManager->persist($user);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }
}
