<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use MoustacheBundle\Event\Events as MoustacheEvent;
use MoustacheBundle\Event\TorrentMissingEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Client\AccessorClient;
use TorrentBundle\Client\AccessorClientInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Client\CacheOutdatedException;
use TorrentBundle\Exception\Client\TorrentAdapterException;
use TorrentBundle\Exception\Torrent\CannotFillTorrentException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;
use TorrentBundle\Filter\TorrentFilterInterface;
use TorrentBundle\Helper\TorrentStorageHelper;
use TorrentBundle\Mapper\TorrentMapperInterface;

class AccessorClientSpec extends ObjectBehavior
{
    public function let(
        AdapterInterface $externalClient,
        TorrentMapperInterface $torrentMapper,
        TorrentStorageHelper $torrentStorageHelper,
        TorrentFilterInterface $torrentFilter,
        EventDispatcherInterface $eventDispatcher,
        CacheInterface $cache,

        TorrentInterface $torrent,
        TorrentInterface $partialTorrent,
        TorrentInterface $completeTorrent
    ) {
        $torrent->getHash()->willReturn('hash');

        $externalClient->add($torrent, Argument::type('string'))->willReturn(null);
        $externalClient->get('cache_value')->willReturn($torrent);

        $torrentMapper->map($torrent, Argument::any())->willReturn($partialTorrent);
        $torrentMapper->mapFiles($partialTorrent, Argument::any())->willReturn($completeTorrent);

        $torrentStorageHelper->get()->willReturn('/torrent/storage/');

        $torrentFilter->getAuthenticatedUserTorrent(3)->willReturn($torrent);
        $torrentFilter->getAllAuthenticatedUserTorrents()->willreturn([$torrent, $torrent]);

        $cache->get(CacheInterface::KEY_TORRENT_HASHES)->willReturn(['hash' => 'cache_value']);
        $cache->isUpToDate()->willReturn(true);

        $eventDispatcher->dispatch(Argument::type('string'), Argument::type(Event::class))->willReturn(null);

        $this->beConstructedWith($externalClient, $torrentMapper, $torrentStorageHelper, $torrentFilter, $eventDispatcher, $cache);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AccessorClient::class);
    }

    public function it_is_an_accessor_client()
    {
        $this->shouldImplement(AccessorClientInterface::class);
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

    public function it_throws_an_exception_when_torrent_is_not_found($torrentFilter)
    {
        $torrentFilter->getAuthenticatedUserTorrent(2)->willReturn(null);

        $this->shouldThrow(TorrentNotFoundException::class)->during('get', [2]);
    }

    public function it_throws_an_exception_when_cache_is_outdated($cache)
    {
        $cache->isUpToDate()->willReturn(false);
        $cache->get(CacheInterface::KEY_TORRENT_HASHES)->willReturn([]);

        $this->shouldThrow(CacheOutdatedException::class)->during('get', [3]);
        $this->shouldThrow(CacheOutdatedException::class)->during('getAll');
    }

    public function it_throws_an_exception_if_a_specific_torrent_is_not_found_because_cache_is_out_of_date($externalClient)
    {
        $externalClient->get('cache_value')->willThrow(new TorrentNotFoundException(3));

        $this->shouldThrow(CacheOutdatedException::class)->during('get', [3]);
        $this->shouldThrow(CacheOutdatedException::class)->during('getAll');
    }

    public function it_throws_an_exception_if_a_specific_torrent_is_not_found_for_unknown_reasons($externalClient)
    {
        $externalClient->get('cache_value')->willThrow(new \Exception());

        $this->shouldThrow(TorrentAdapterException::class)->during('get', [3]);
        $this->shouldThrow(TorrentAdapterException::class)->during('getAll');
    }

    public function it_adds_a_torrent($torrent, $externalClient)
    {
        $externalClient->add($torrent, Argument::type('string'))->shouldBeCalledTimes(1);

        $this->add($torrent);
    }

    public function it_maps_an_external_torrent($torrent, $partialTorrent, $completeTorrent, $torrentMapper)
    {
        $torrentMapper->map($torrent, Argument::any())->shouldBeCalledTimes(4);
        $torrentMapper->mapFiles($partialTorrent, Argument::any())->shouldBeCalledTimes(4);

        $this->add($torrent)->shouldReturn($completeTorrent);
        $this->get(3)->shouldReturn($completeTorrent);
        $this->getAll()->shouldReturn([$completeTorrent, $completeTorrent]);
    }

    public function it_dispatches_a_torrent_added_event_when_torrent_is_added($eventDispatcher, $torrent)
    {
        $eventDispatcher->dispatch(Events::AFTER_TORRENT_ADDED, Argument::type(TorrentAfterEvent::class))->shouldBeCalledTimes(1);

        $this->add($torrent);
    }

    public function it_dispatches_a_torrent_get_event_when_torrent_is_retrieved($eventDispatcher)
    {
        $eventDispatcher->dispatch(Events::AFTER_TORRENT_GET, Argument::type(TorrentAfterEvent::class))->shouldBeCalledTimes(3);

        $this->get(3);
        $this->getAll();
    }

    public function it_dispatches_a_torrent_missing_event_when_torrent_hash_is_not_found_in_cache($cache, $eventDispatcher)
    {
        $cache->get(CacheInterface::KEY_TORRENT_HASHES)->willReturn([]);

        $eventDispatcher->dispatch(MoustacheEvent::TORRENT_MISSING, Argument::type(TorrentMissingEvent::class))->shouldBeCalledTimes(3);

        $this->get(3);
        $this->getAll();
    }
}
