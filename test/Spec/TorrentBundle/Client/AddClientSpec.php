<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Client\AddClient;
use TorrentBundle\Client\AddClientInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Client\TorrentAdapterException;
use TorrentBundle\Exception\Torrent\CannotFillTorrentException;
use TorrentBundle\Helper\TorrentStorageHelper;
use TorrentBundle\Mapper\TorrentMapperInterface;

class AddClientSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        AdapterInterface $externalClient,
        TorrentMapperInterface $torrentMapper,
        TorrentStorageHelper $torrentStorageHelper,

        TorrentInterface $torrent,
        TorrentInterface $partialTorrent,
        TorrentInterface $completeTorrent
    ) {
        $torrent->getHash()->willReturn('hash');

        $eventDispatcher->dispatch(Argument::type('string'), Argument::type(Event::class))->willReturn(null);

        $externalClient->add($torrent, Argument::type('string'))->willReturn(null);

        $torrentMapper->map($torrent, Argument::any())->willReturn($partialTorrent);
        $torrentMapper->mapFiles($partialTorrent, Argument::any())->willReturn($completeTorrent);

        $torrentStorageHelper->get()->willReturn('/torrent/storage/');

        $this->beConstructedWith($eventDispatcher, $externalClient, $torrentMapper, $torrentStorageHelper);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AddClient::class);
    }

    public function it_is_an_add_client()
    {
        $this->shouldImplement(AddClientInterface::class);
    }

    public function it_throws_an_exception_when_failing_to_add_a_torrent($torrent, $externalClient)
    {
        $externalClient->add($torrent, Argument::type('string'))->willThrow(new \Exception());

        $this->shouldThrow(TorrentAdapterException::class)->during('add', [$torrent]);
    }

    public function it_throws_an_exception_when_failing_to_map_a_torrent($torrent, $torrentMapper)
    {
        $torrentMapper->map($torrent, Argument::any())->willThrow(new \Exception());

        $this->shouldThrow(CannotFillTorrentException::class)->during('add', [$torrent]);
    }

    public function it_adds_a_torrent($torrent, $externalClient)
    {
        $externalClient->add($torrent, Argument::type('string'))->shouldBeCalledTimes(1);

        $this->add($torrent);
    }

    public function it_maps_an_external_torrent($torrent, $partialTorrent, $completeTorrent, $torrentMapper)
    {
        $torrentMapper->map($torrent, Argument::any())->shouldBeCalledTimes(1);
        $torrentMapper->mapFiles($partialTorrent, Argument::any())->shouldBeCalledTimes(1);

        $this->add($torrent)->shouldReturn($completeTorrent);
    }

    public function it_dispatches_a_torrent_added_event_when_torrent_is_added($eventDispatcher, $torrent)
    {
        $eventDispatcher->dispatch(Events::AFTER_TORRENT_ADDED, Argument::type(TorrentAfterEvent::class))->shouldBeCalledTimes(1);

        $this->add($torrent);
    }
}
