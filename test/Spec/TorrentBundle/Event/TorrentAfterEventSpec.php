<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Event;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\TorrentAfterEvent;

class TorrentAfterEventSpec extends ObjectBehavior
{
    public function let(TorrentInterface $torrent)
    {
        $this->beConstructedWith($torrent);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentAfterEvent::class);
    }

    public function it_sets_and_return_torrent($torrent, TorrentInterface $newTorrent)
    {
        $this->getTorrent()->shouldReturn($torrent);

        $this->setTorrent($newTorrent);

        $this->getTorrent()->shouldReturn($newTorrent);
    }
}
