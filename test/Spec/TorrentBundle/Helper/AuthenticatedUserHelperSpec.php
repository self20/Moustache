<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Helper;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Exception\Permission\NoAuthenticatedUserException;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Helper\HelperGetterInterface;

class AuthenticatedUserHelperSpec extends ObjectBehavior
{
    public function let(
        TokenStorageInterface $tokenStorage,

        TokenInterface $token,
        UserInterface $user
    ) {
        $token->getUser()->willReturn($user);
        $tokenStorage->getToken()->willReturn($token);

        $this->beConstructedWith($tokenStorage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AuthenticatedUserHelper::class);
    }

    public function it_is_a_helper_getter()
    {
        $this->shouldHaveType(HelperGetterInterface::class);
    }

    public function it_tells_if_there_is_an_authenticated_user_or_not($tokenStorage)
    {
        $this->isEmpty()->shouldReturn(false);

        $tokenStorage->getToken()->willReturn(null);

        $this->isEmpty()->shouldReturn(true);
    }

    public function it_returns_authenticated_user_when_it_is_available($tokenStorage, $user)
    {
        $this->getWhenAvailable()->shouldReturn($user);

        $tokenStorage->getToken()->willReturn(null);

        $this->getWhenAvailable()->shouldReturn(null);
    }

    public function it_throws_an_exception_if_token_storage_is_empty($tokenStorage)
    {
        $tokenStorage->getToken()->willReturn(null);

        $this->shouldThrow(NoAuthenticatedUserException::class)->during('get');
    }

    public function it_throws_an_exception_if_token_is_invalid($token)
    {
        $token->getUser()->willReturn(new \StdClass());

        $this->shouldThrow(NoAuthenticatedUserException::class)->during('get');
    }

    public function it_returns_user($user)
    {
        $this->get()->shouldReturn($user);
    }
}
