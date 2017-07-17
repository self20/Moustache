<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use StandardBundle\CanDownload;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Client\ExternalTorrentGetter;
use TorrentBundle\Client\LifeCycleClient;
use TorrentBundle\Client\LifeCycleClientInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Torrent\CannotStartTorrentException;
use TorrentBundle\Exception\Torrent\CannotStopTorrentException;

class LifeCycleClientSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        AdapterInterface $externalClient,
        ExternalTorrentGetter $externalTorrentGetter,

        TorrentInterface $torrent,
        TorrentInterface $externalTorrent
    ) {
        $torrent->getHash()->willReturn('hash');
        $torrent->getFriendlyName()->willReturn('friendly name');
        $torrent->setStatus(Argument::type('int'))->willReturn();

        $externalClient->start($externalTorrent)->willReturn();
        $externalClient->stop($externalTorrent)->willReturn();

        $externalTorrentGetter->get('hash')->willReturn($externalTorrent);

        $this->beConstructedWith($eventDispatcher, $externalClient, $externalTorrentGetter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LifeCycleClient::class);
    }

    public function it_is_an_life_cycle_client()
    {
        $this->shouldImplement(LifeCycleClientInterface::class);
    }

    public function it_throws_an_exception_when_adapter_failed_to_start_a_torrent($torrent, $externalClient, $externalTorrent)
    {
        $externalClient->start($externalTorrent)->willThrow(new \Exception());

        $this->shouldThrow(CannotStartTorrentException::class)->during('start', [$torrent]);
        $this->shouldThrow(CannotStartTorrentException::class)->during('startWithoutLimits', [$torrent]);
    }

    public function it_throws_an_exception_when_adapter_failed_to_stop_a_torrent($torrent, $externalClient, $externalTorrent)
    {
        $externalClient->stop($externalTorrent)->willThrow(new \Exception());

        $this->shouldThrow(CannotStopTorrentException::class)->during('stop', [$torrent]);
    }

    public function it_starts_a_torrent($torrent, $externalClient, $externalTorrent)
    {
        $externalClient->start($externalTorrent)->shouldBeCalledTimes(2);

        $this->start($torrent);
        $this->startWithoutLimits($torrent);
    }

    public function it_sets_downloading_status_when_torrent_is_started($torrent)
    {
        $torrent->setStatus(CanDownload::STATUS_DOWNLOADING)->shouldBeCalledTimes(2);

        $this->start($torrent);
        $this->startWithoutLimits($torrent);
    }

    public function it_dispatches_an_event_when_torrent_is_started($eventDispatcher, $torrent)
    {
        $eventDispatcher->dispatch(Events::AFTER_TORRENT_STARTED, Argument::type(TorrentAfterEvent::class))->shouldBeCalledTimes(2);

        $this->start($torrent);
        $this->startWithoutLimits($torrent);
    }

    public function it_stops_a_torrent($torrent, $externalClient, $externalTorrent)
    {
        $externalClient->stop($externalTorrent)->shouldBeCalledTimes(1);

        $this->stop($torrent);
    }

    public function it_sets_stop_status_when_torrent_is_stopped($torrent)
    {
        $torrent->setStatus(CanDownload::STATUS_STOP)->shouldBeCalledTimes(1);

        $this->stop($torrent);
    }

    public function it_dispatches_an_event_when_torrent_is_stopped($eventDispatcher, $torrent)
    {
        $eventDispatcher->dispatch(Events::AFTER_TORRENT_STOPPED, Argument::type(TorrentAfterEvent::class))->shouldBeCalledTimes(1);

        $this->stop($torrent);
    }
}
