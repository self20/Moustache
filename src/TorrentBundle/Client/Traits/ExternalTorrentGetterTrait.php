<?php

declare(strict_types=1);

namespace TorrentBundle\Client\Traits;

use MoustacheBundle\Event\Events;
use MoustacheBundle\Event\TorrentMissingEvent;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Exception\Client\CacheOutdatedException;
use TorrentBundle\Exception\Client\TorrentAdapterException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;

trait ExternalTorrentGetterTrait
{
    /**
     * @var AdapterInterface
     */
    private $externalClient;

    /**
     * @var TorrentMapperInterface
     */
    private $torrentMapper;

    /**
     * @var TorrentStorageHelper
     */
    private $torrentStorageHelper;

    /**
     * @var TorrentFilterInterface
     */
    private $torrentFilter;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var CacheInterface
     */
    private $cache;

    private function getExternalTorrent(string $hash)
    {
        if (!isset($this->cache->get(CacheInterface::KEY_TORRENT_HASHES)[$hash]) && !$this->cache->isUpToDate()) {
            // Torrent in BDD: present | Torrent in cache: absent | Torrent in external: ?
            throw new CacheOutdatedException(sprintf('A torrent with id “%s” was requested from external client RPC but is absent from cache. Cache needs update.', $hash));
        }

        if (!isset($this->cache->get(CacheInterface::KEY_TORRENT_HASHES)[$hash])) {
            // Torrent in BDD: present | Torrent in cache: absent | Torrent in external: absent
            $this->eventDispatcher->dispatch(Events::TORRENT_MISSING, new TorrentMissingEvent($hash));

            return;
        }

        try {
            return $this->externalClient->get($this->cache->get(CacheInterface::KEY_TORRENT_HASHES)[$hash]);
        } catch (TorrentNotFoundException $ex) {
            // Torrent in BDD: present | Torrent in cache: present | Torrent in external: absent
            throw new CacheOutdatedException(sprintf('A torrent with id “%s” was requested from external client RPC, as it exists in cache, but it was absent from external client. Cache needs update.', $hash), 0, $ex);
        } catch (\Exception $ex) {
            throw new TorrentAdapterException(sprintf('An unknown error occured when requesting torrent with id “%s”.', $hash), 0, $ex);
        }
    }
}
