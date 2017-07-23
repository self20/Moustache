<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\EventListener\TorrentStartedDispatcherListener;

class TorrentStartedDispatcherListenerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,

        TorrentAfterEvent $event,
        TorrentInterface $torrent
    ) {
        $torrent->isStarted()->willReturn(true);
        $event->getTorrent()->willReturn($torrent);

        $eventDispatcher->dispatch(Events::AFTER_TORRENT_STARTED, $event)->willReturn();

        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentStartedDispatcherListener::class);
    }

    public function it_does_nothing_if_torrent_is_not_started($event, $eventDispatcher, $torrent)
    {
        $torrent->isStarted()->willReturn(false);

        $eventDispatcher->dispatch(Argument::cetera())->shouldNotBeCalled();

        $this->afterTorrentAdded($event);
    }

    public function it_dispatches_an_event_if_torrent_is_started_after_being_added($event, $eventDispatcher)
    {
        $eventDispatcher->dispatch(Events::AFTER_TORRENT_STARTED, $event)->shouldBeCalledTimes(1);

        $this->afterTorrentAdded($event);
    }
}
