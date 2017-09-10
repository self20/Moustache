<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Rico\Lib\StringUtils;
use TorrentBundle\Client\CanStopTorrent;
use TorrentBundle\DiskSpace\DiskSpaceChecker;
use TorrentBundle\DiskSpace\DiskSpaceReader;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\EventListener\DiskSpaceCheckerListener;
use TorrentBundle\Exception\Permission\NotEnoughDiskSpaceException;
use TorrentBundle\Helper\HelperGetterInterface;

class DiskSpaceCheckerListenerSpec extends ObjectBehavior
{
    public function let(
        DiskSpaceChecker $diskSpaceChecker,
        DiskSpaceReader $diskSpaceReader,
        CanStopTorrent $stopClient,
        HelperGetterInterface $torrentStorageHelper,
        LoggerInterface $logger,
        StringUtils $stringUtils,

        TorrentAfterEvent $event,
        TorrentInterface $torrent
    ) {
        $torrent->getHash()->willReturn('hash');
        $torrent->getFriendlyName()->willReturn('friendly name');
        $torrent->getVirtualUsedByteSize()->willReturn(12303422);
        $event->getTorrent()->willReturn($torrent);

        $diskSpaceChecker->checkEnoughDiskSpace()->willReturn(false);

        $diskSpaceReader->getVirtualFreeSpace()->willReturn(634303422);

        $stopClient->stop($torrent)->willReturn();

        $stringUtils->humanFilesize(12303422)->willReturn('12,3MB');
        $stringUtils->humanFilesize(634303422)->willReturn('634,3MB');

        $this->beConstructedWith($diskSpaceChecker, $diskSpaceReader, $stopClient, $torrentStorageHelper, $logger, $stringUtils);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DiskSpaceCheckerListener::class);
    }

    public function it_does_nothing_if_available_virtual_space_is_sufficient($diskSpaceChecker, $event, $stopClient, $torrent)
    {
        $diskSpaceChecker->checkEnoughDiskSpace()->willReturn(true);

        $stopClient->stop($torrent)->shouldNotBeCalled();

        $this->afterTorrentStarted($event)->shouldReturn(null);
    }

    public function it_stopped_the_torrent_and_throws_an_exception_if_virtual_space_is_not_sufficient($event, $stopClient, $torrent)
    {
        $stopClient->stop($torrent)->shouldBeCalledTimes(1);

        $this->shouldThrow(NotEnoughDiskSpaceException::class)->during('afterTorrentStarted', [$event]);
    }
}
