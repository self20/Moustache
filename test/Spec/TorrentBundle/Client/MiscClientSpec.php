<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Client\MiscClient;
use TorrentBundle\Client\MiscClientInterface;
use TorrentBundle\Entity\TorrentInterface;

class MiscClientSpec extends ObjectBehavior
{
    public function let(
        AdapterInterface $externalClient,
        CacheInterface $cache,

        TorrentInterface $torrent
    ) {
        $externalClient->getSessionToken()->willReturn('session token');
        $externalClient->getCacheValues()->willReturn(['cache', 'values']);

        $this->beConstructedWith($externalClient, $cache);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MiscClient::class);
    }

    public function it_is_an_misc_client()
    {
        $this->shouldImplement(MiscClientInterface::class);
    }

    public function it_returns_external_client_session_token()
    {
        $this->getSessionToken()->shouldReturn('session token');
    }

    public function it_reannounces_a_torrent($externalClient, $torrent)
    {
        $externalClient->reannounce($torrent)->shouldBeCalledTimes(1);

        $this->reannounce($torrent);
    }

    public function it_updates_the_cache_values($cache)
    {
        $cache->set(CacheInterface::KEY_TORRENT_HASHES, ['cache', 'values'])->shouldBeCalledTimes(1);

        $this->updateCache();
    }

    public function it_verifies_a_torrent($externalClient, $torrent)
    {
        $externalClient->verify($torrent)->shouldBeCalledTimes(1);

        $this->verify($torrent);
    }
}
