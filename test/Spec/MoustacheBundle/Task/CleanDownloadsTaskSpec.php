<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Task;

use MoustacheBundle\Service\TorrentLinkGeneratorInterface;
use MoustacheBundle\Task\CleanDownloadsTask;
use MoustacheBundle\Task\TaskInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;

class CleanDownloadsTaskSpec extends ObjectBehavior
{
    const TEST_DIR = __DIR__.'/files';

    public function let(TorrentLinkGeneratorInterface $torrentLinkGenerator, Filesystem $filesystem)
    {
        $torrentLinkGenerator->generatePartialAbsoluteLink()->willReturn(self::TEST_DIR);

        $this->beConstructedWith($torrentLinkGenerator, $filesystem, 120);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CleanDownloadsTask::class);
    }

    public function it_is_a_task()
    {
        $this->shouldImplement(TaskInterface::class);
    }

    public function it_sets_up_itself()
    {
        $this->setup();
    }

    public function it_removes_expired_downloadable_files($filesystem)
    {
        $this->init();

        $filesystem->remove(self::TEST_DIR.'/test.recent')->shouldNotBeCalled();
        $filesystem->remove(self::TEST_DIR.'/test.old')->shouldBeCalledTimes(1);
        $filesystem->remove(self::TEST_DIR)->shouldBeCalledTimes(1);

        $this->run()->shouldReturn(0);

        $this->unlink();
    }

    public function it_returns_the_list_of_removed_files()
    {
        $this->init();

        $this->run();

        $this->teardown()->shouldReturn([self::TEST_DIR.'/test.old']);

        $this->unlink();
    }

    private function init()
    {
        touch(self::TEST_DIR.'/test.recent', time());
        touch(self::TEST_DIR.'/test.old', 1000, 1000);
    }

    private function unlink()
    {
        unlink(self::TEST_DIR.'/test.recent');
        unlink(self::TEST_DIR.'/test.old');
    }
}
