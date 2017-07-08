<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Command;

use MoustacheBundle\Command\GenerateSignupCommand;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Routing\RouterInterface;
use TorrentBundle\Manager\UserManager;
use TorrentBundle\Repository\UserRepository;

class GenerateSignupCommandSpec extends ObjectBehavior
{
    public function let(UserRepository $userRepository, UserManager $userManager, RouterInterface $router)
    {
        $this->beConstructedWith($userRepository, $userManager, $router);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GenerateSignupCommand::class);
    }

    public function it_is_a_command()
    {
        $this->shouldImplement(Command::class);
    }
}
