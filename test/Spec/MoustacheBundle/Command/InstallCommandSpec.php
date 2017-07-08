<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Command;

use MoustacheBundle\Command\InstallCommand;
use MoustacheBundle\Task\TaskInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;

class InstallCommandSpec extends ObjectBehavior
{
    public function let(TaskInterface $symlinkParametersTask)
    {
        $this->beConstructedWith($symlinkParametersTask);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(InstallCommand::class);
    }

    public function it_is_a_command()
    {
        $this->shouldImplement(Command::class);
    }
}
