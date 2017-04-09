<?php

declare(strict_types=1);

namespace Spec\Test\TorrentBundle\Helper;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Exception\Permission\NoAuthenticatedUserException;
use TorrentBundle\Helper\HelperGetterInterface;

class AuthenticatedUserHelperSpec extends ObjectBehavior
{
    public function let(
        TokenStorageInterface $tokenInterface,

        TokenInterface $token,
        UserInterface $user
    ) {
        $tokenInterface->getToken()->willReturn($token);
        $token->getUser()->willReturn($user);

        $this->beConstructedWith($tokenInterface);
    }

    public function it_is_a_helper_getter()
    {
        $this->shouldImplement(HelperGetterInterface::class);
    }

    public function it_throws_an_exception_when_token_is_empty($tokenInterface)
    {
        $tokenInterface->getToken()->willReturn(null);

        $this->shouldThrow(NoAuthenticatedUserException::class)->during('get');
    }

    public function it_throws_an_exception_when_token_does_not_return_a_user($token)
    {
        $token->getUser()->willReturn(new \stdClass());

        $this->shouldThrow(NoAuthenticatedUserException::class)->during('get');
    }

    public function it_returns_null_when_token_is_empty_in_lease_mode($tokenInterface)
    {
        $tokenInterface->getToken()->willReturn(null);

        $this->getWhenAvailable()->shouldReturn(null);
    }

    public function it_returns_null_when_token_does_not_return_a_user_in_lease_mode($token)
    {
        $token->getUser()->willReturn(new \stdClass());

        $this->getWhenAvailable()->shouldReturn(null);
    }

    public function it_returns_current_logged_user($user)
    {
        $this->get()->shouldReturn($user);
    }

    public function it_returns_user_existence()
    {
        $this->isEmpty()->shouldReturn(false);
    }

    public function it_returns_user_existence_when_token_is_empty($tokenInterface)
    {
        $tokenInterface->getToken()->willReturn(null);

        $this->isEmpty()->shouldReturn(true);
    }

    public function it_returns_user_existence_when_token_does_not_return_a_user($token)
    {
        $token->getUser()->willReturn(new \stdClass());

        $this->isEmpty()->shouldReturn(true);
    }
}
