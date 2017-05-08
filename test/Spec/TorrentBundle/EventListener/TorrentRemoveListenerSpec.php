<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\EventListener;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\EventListener\TorrentRemoveListener;
use TorrentBundle\Manager\TorrentManager;

class TorrentRemoveListenerSpec extends ObjectBehavior
{
    public function let(
        TorrentManager $torrentManager,

        TorrentAfterEvent $event,
        TorrentInterface $torrent
    ) {
        $event->getTorrent()->willReturn($torrent);

        $torrentManager->remove($torrent)->willReturn($torrentManager);

        $this->beConstructedWith($torrentManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentRemoveListener::class);
    }

    public function it_removes_a_torrent_and_save_it($event, $torrent, $torrentManager)
    {
        $torrentManager->remove($torrent)->shouldBeCalledTimes(1);
        $torrentManager->save()->shouldBeCalledTimes(1);

        $this->afterTorrentRemoved($event)->shouldReturn($event);
    }
}
