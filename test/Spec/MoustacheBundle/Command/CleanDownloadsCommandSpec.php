<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Command;

use MoustacheBundle\Command\CleanDownloadsCommand;
use MoustacheBundle\Task\TaskInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;

class CleanDownloadsCommandSpec extends ObjectBehavior
{
    public function let(TaskInterface $cleanDownloadsTask)
    {
        $this->beConstructedWith($cleanDownloadsTask);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CleanDownloadsCommand::class);
    }

    public function it_is_a_command()
    {
        $this->shouldImplement(Command::class);
    }
}
