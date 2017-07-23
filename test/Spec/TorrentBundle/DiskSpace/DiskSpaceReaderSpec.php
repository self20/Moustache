<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\DiskSpace;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Client\CanGetTorrent;
use TorrentBundle\DiskSpace\DiskSpaceReader;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Helper\HelperGetterInterface;

class DiskSpaceReaderSpec extends ObjectBehavior
{
    public function let(
        HelperGetterInterface $torrentStorageHelper,
        CanGetTorrent $client,

        TorrentInterface $torrent1,
        TorrentInterface $torrent2,
        TorrentInterface $torrent3
    ) {
        $torrent1->isStarted()->willReturn(true);
        $torrent2->isStarted()->willReturn(false);
        $torrent3->isStarted()->willReturn(true);

        $torrent1->getVirtualUsedByteSize()->willReturn(1223050340);
        $torrent2->getVirtualUsedByteSize()->willReturn(3000000000);
        $torrent3->getVirtualUsedByteSize()->willReturn(0);

        $client->getAll()->willReturn([$torrent1, $torrent2, $torrent3]);
        $torrentStorageHelper->get()->willReturn('/');

        $this->beConstructedWith($torrentStorageHelper, $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DiskSpaceReader::class);
    }

    public function it_returns_total_disk_space()
    {
        $this->getTotalSpace()->shouldBeInteger();
    }

    public function it_returns_free_disk_space()
    {
        $this->getFreeSpace()->shouldBeInteger();
    }

    public function it_returns_used_disk_space()
    {
        $this->getUsedSpace()->shouldBeInteger();
    }

    public function it_returns_virtual_free_space()
    {
        $this->getVirtualFreeSpace()->shouldBeInteger();
    }

    public function it_returns_virtual_used_space()
    {
        $this->getVirtualUsedSpace()->shouldReturn(1223050340);
    }
}
