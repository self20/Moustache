<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\EventListener;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\EventListener\TorrentAddListener;
use TorrentBundle\Manager\TorrentManager;

class TorrentAddListenerSpec extends ObjectBehavior
{
    public function let(
        TorrentManager $torrentManager,

        TorrentAfterEvent $event,
        TorrentInterface $torrent
    ) {
        $event->getTorrent()->willReturn($torrent);

        $torrentManager->persist($torrent)->willReturn($torrentManager);

        $this->beConstructedWith($torrentManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentAddListener::class);
    }

    public function it_persists_a_torrent_and_save_it($event, $torrent, $torrentManager)
    {
        $torrentManager->persist($torrent)->shouldBeCalledTimes(1);
        $torrentManager->save()->shouldBeCalledTimes(1);

        $this->afterTorrentAdded($event)->shouldReturn($event);
    }
}
