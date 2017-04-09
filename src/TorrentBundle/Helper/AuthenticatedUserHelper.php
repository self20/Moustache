<?php

declare(strict_types=1);

namespace TorrentBundle\Helper;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Exception\Permission\NoAuthenticatedUserException;

class AuthenticatedUserHelper implements HelperGetterInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return null === $this->getWhenAvailable();
    }

    /**
     * @return UserInterface|null
     */
    public function getWhenAvailable()
    {
        $token = $this->tokenStorage->getToken();

        if (!$this->isValidToken($token)) {
            return;
        }

        return $token->getUser();
    }

    /**
     * @throws NoAuthenticatedUserException
     *
     * @return UserInterface
     */
    public function get(): UserInterface
    {
        $token = $this->tokenStorage->getToken();

        if (!$this->isValidToken($token)) {
            throw new NoAuthenticatedUserException('This action needed an authenticated user.');
        }

        return $token->getUser();
    }

    private function isValidToken(TokenInterface $token = null): bool
    {
        return
            null !== $token &&
            $token->getUser() instanceof UserInterface
        ;
    }
}
