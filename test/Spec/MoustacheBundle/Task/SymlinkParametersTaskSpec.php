<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Task;

use MoustacheBundle\Exception\Permission\SystemPermissionException;
use MoustacheBundle\Task\SymlinkParametersTask;
use MoustacheBundle\Task\TaskInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Filesystem\Filesystem;

class SymlinkParametersTaskSpec extends ObjectBehavior
{
    public function let(
        Filesystem $filesystem
    ) {
        $this->beConstructedWith($filesystem, '/root/dir', '.');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SymlinkParametersTask::class);
    }

    public function it_is_a_task()
    {
        $this->shouldImplement(TaskInterface::class);
    }

    public function it_sets_up_itself()
    {
        $this->setup();
    }

    public function it_fails_to_teardown_if_system_conf_directory_is_not_writable($filesystem)
    {
        $this->beConstructedWith($filesystem, '/root/dir', '/some_non_existing_dir');

        $this->shouldThrow(SystemPermissionException::class)->during('teardown');
    }

    public function it_fails_to_run_if_system_conf_directory_is_not_writable($filesystem)
    {
        $this->beConstructedWith($filesystem, '/root/dir', '/some_non_existing_dir');

        $this->shouldThrow(SystemPermissionException::class)->during('run');
    }

    public function it_does_nothing_on_run_if_the_file_already_exists($filesystem)
    {
        touch('./moustache.yml');
        $filesystem->symlink(Argument::type('string'), './moustache.yml')->shouldNotBeCalled();

        $this->run()->shouldReturn(0);

        unlink('./moustache.yml');
    }

    public function it_teardown_itself_by_removing_configuration_file($filesystem)
    {
        $filesystem->remove('./moustache.yml')->shouldBeCalledTimes(1);

        $this->teardown();
    }

    public function it_runs_itself_by_creating_a_system_configuration_file($filesystem)
    {
        $filesystem->symlink(Argument::type('string'), './moustache.yml')->shouldBeCalledTimes(1);

        $this->run()->shouldReturn(0);
    }
}
