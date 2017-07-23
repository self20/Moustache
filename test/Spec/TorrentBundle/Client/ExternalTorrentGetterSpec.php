<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Client\ExternalTorrentGetter;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentMissingEvent;
use TorrentBundle\Exception\Client\CacheOutdatedException;
use TorrentBundle\Exception\Client\TorrentAdapterException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;

class ExternalTorrentGetterSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        AdapterInterface $externalClient,
        CacheInterface $cache,

        TorrentInterface $torrent
    ) {
        $torrent->getHash()->willReturn('hash');

        $eventDispatcher->dispatch(Argument::type('string'), Argument::type(Event::class))->willReturn(null);

        $externalClient->add($torrent, Argument::type('string'))->willReturn(null);
        $externalClient->get('cache_value')->willReturn($torrent);

        $cache->get(CacheInterface::KEY_TORRENT_HASHES)->willReturn(['hash' => 'cache_value']);
        $cache->isUpToDate()->willReturn(true);

        $this->beConstructedWith($eventDispatcher, $externalClient, $cache);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ExternalTorrentGetter::class);
    }

    public function it_throws_an_exception_when_cache_is_outdated($cache)
    {
        $cache->isUpToDate()->willReturn(false);
        $cache->get(CacheInterface::KEY_TORRENT_HASHES)->willReturn([]);

        $this->shouldThrow(CacheOutdatedException::class)->during('get', ['hash']);
    }

    public function it_throws_an_exception_if_a_specific_torrent_is_not_found_because_cache_is_out_of_date($externalClient)
    {
        $externalClient->get('cache_value')->willThrow(new TorrentNotFoundException(3));

        $this->shouldThrow(CacheOutdatedException::class)->during('get', ['hash']);
    }

    public function it_throws_an_exception_if_a_specific_torrent_is_not_found_for_unknown_reasons($externalClient)
    {
        $externalClient->get('cache_value')->willThrow(new \Exception());

        $this->shouldThrow(TorrentAdapterException::class)->during('get', ['hash']);
    }

    public function it_dispatches_a_torrent_missing_event_when_torrent_hash_is_not_found_in_cache($cache, $eventDispatcher)
    {
        $cache->get(CacheInterface::KEY_TORRENT_HASHES)->willReturn([]);

        $eventDispatcher->dispatch(Events::TORRENT_MISSING, Argument::type(TorrentMissingEvent::class))->shouldBeCalledTimes(1);

        $this->get('hash')->shouldReturn(null);
    }

    public function it_returns_an_external_torrent($torrent)
    {
        $this->get('hash')->shouldReturn($torrent);
    }
}
