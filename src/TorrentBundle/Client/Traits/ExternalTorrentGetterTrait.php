<?php

declare(strict_types=1);

namespace TorrentBundle\Client\Traits;

use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Exception\TorrentNotFoundException;

trait ExternalTorrentGetterTrait
{
    private function getExternalTorrent(string $hash)
    {
        if (!isset($this->cache->get(CacheInterface::KEY_TORRENT_HASHES)[$hash])) {
            // @TODO Make a special Exception here that dispatched an event. Make a listener to reload the cache on that event.
            throw new TorrentNotFoundException(sprintf('A torrent with id “%s” was requested from external client RPC, but it is absent from cache.', $hash));
        }

        try {
            return $this->externalClient->get($this->cache->get(CacheInterface::KEY_TORRENT_HASHES)[$hash]);
        } catch (RuntimeException $ex) {
            throw new TorrentNotFoundException(sprintf('A torrent with id “%s” was requested from external client RPC, but it does not exist.', $hash), 0, $ex);
        }
    }
}
