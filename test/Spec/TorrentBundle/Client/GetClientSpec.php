<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Client\ExternalTorrentGetter;
use TorrentBundle\Client\GetClient;
use TorrentBundle\Client\GetClientInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;
use TorrentBundle\Filter\TorrentFilterInterface;
use TorrentBundle\Mapper\TorrentMapperInterface;

class GetClientSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        AdapterInterface $externalClient,
        TorrentMapperInterface $torrentMapper,
        TorrentFilterInterface $torrentFilter,
        ExternalTorrentGetter $externalTorrentGetter,

        TorrentInterface $torrent,
        TorrentInterface $partialTorrent,
        TorrentInterface $completeTorrent
    ) {
        $torrent->getHash()->willReturn('hash');

        $eventDispatcher->dispatch(Argument::type('string'), Argument::type(Event::class))->willReturn(null);

        $externalClient->add($torrent, Argument::type('string'))->willReturn(null);

        $torrentMapper->map($torrent, Argument::any())->willReturn($partialTorrent);
        $torrentMapper->mapFiles($partialTorrent, Argument::any())->willReturn($completeTorrent);

        $torrentFilter->getAuthenticatedUserTorrent(3)->willReturn($torrent);
        $torrentFilter->getAllAuthenticatedUserTorrents()->willreturn([$torrent, $torrent]);

        $externalTorrentGetter->get('hash')->willReturn($torrent);

        $this->beConstructedWith($eventDispatcher, $externalClient, $torrentMapper, $torrentFilter, $externalTorrentGetter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetClient::class);
    }

    public function it_is_an_get_client()
    {
        $this->shouldImplement(GetClientInterface::class);
    }

    public function it_throws_an_exception_when_torrent_is_not_found($torrentFilter)
    {
        $torrentFilter->getAuthenticatedUserTorrent(2)->willReturn(null);

        $this->shouldThrow(TorrentNotFoundException::class)->during('get', [2]);
    }

    public function it_maps_an_external_torrent($torrent, $partialTorrent, $completeTorrent, $torrentMapper)
    {
        $torrentMapper->map($torrent, Argument::any())->shouldBeCalledTimes(3);
        $torrentMapper->mapFiles($partialTorrent, Argument::any())->shouldBeCalledTimes(3);

        $this->get(3)->shouldReturn($completeTorrent);
        $this->getAll()->shouldReturn([$completeTorrent, $completeTorrent]);
    }

    public function it_dispatches_a_torrent_get_event_when_torrent_is_retrieved($eventDispatcher)
    {
        $eventDispatcher->dispatch(Events::AFTER_TORRENT_GET, Argument::type(TorrentAfterEvent::class))->shouldBeCalledTimes(3);

        $this->get(3);
        $this->getAll();
    }
}
