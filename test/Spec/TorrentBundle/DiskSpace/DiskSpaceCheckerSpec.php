<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\DiskSpace;

use PhpSpec\ObjectBehavior;
use TorrentBundle\DiskSpace\DiskSpaceChecker;
use TorrentBundle\DiskSpace\DiskSpaceReader;

class DiskSpaceCheckerSpec extends ObjectBehavior
{
    public function let(DiskSpaceReader $diskSpaceReader)
    {
        $diskSpaceReader->getVirtualFreeSpace()->willReturn(524000000);

        $this->beConstructedWith($diskSpaceReader);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DiskSpaceChecker::class);
    }

    public function it_tells_if_when_there_is_enough_space_available()
    {
        $this->checkEnoughDiskSpace()->shouldReturn(true);
    }

    public function it_tells_if_there_are_not_enough_space_available($diskSpaceReader)
    {
        $diskSpaceReader->getVirtualFreeSpace()->willReturn(23000000);

        $this->checkEnoughDiskSpace()->shouldReturn(false);
    }
}
