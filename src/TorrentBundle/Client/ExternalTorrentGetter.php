<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentMissingEvent;
use TorrentBundle\Exception\Client\CacheOutdatedException;
use TorrentBundle\Exception\Client\TorrentAdapterException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;

class ExternalTorrentGetter
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var AdapterInterface
     */
    private $externalClient;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param AdapterInterface         $externalClient
     * @param CacheInterface           $cache
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, AdapterInterface $externalClient, CacheInterface $cache)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->externalClient = $externalClient;
        $this->cache = $cache;
    }

    /**
     * @param string $hash
     *
     * @throws CacheOutdatedException
     * @throws TorrentAdapterException
     *
     * @return mixed
     */
    public function get(string $hash)
    {
        $this->checkHashInCache($hash);

        try {
            return $this->externalClient->get($this->cache->get(CacheInterface::KEY_TORRENT_HASHES)[$hash]);
        } catch (TorrentNotFoundException $ex) {
            // Torrent in BDD: present | Torrent in cache: present | Torrent in external: absent
            throw new CacheOutdatedException(sprintf('A torrent with id “%s” was requested from external client RPC, as it exists in cache, but it was absent from external client. Cache needs update.', $hash), 0, $ex);
        } catch (\Exception $ex) {
            throw new TorrentAdapterException(sprintf('An unknown error occured when requesting torrent with id “%s”.', $hash), 0, $ex);
        }
    }

    public function checkHashInCache(string $hash)
    {
        if (!$this->isHashInCache($hash) && !$this->cache->isUpToDate()) {
            // Torrent in BDD: present | Torrent in cache: absent | Torrent in external: ?
            throw new CacheOutdatedException(sprintf('A torrent with id “%s” was requested from external client RPC but is absent from cache. Cache needs update.', $hash));
        }

        if (!$this->isHashInCache($hash)) {
            // Torrent in BDD: present | Torrent in cache: absent | Torrent in external: absent
            $this->eventDispatcher->dispatch(Events::TORRENT_MISSING, new TorrentMissingEvent($hash));
        }
    }

    private function isHashInCache(string $hash): bool
    {
        return isset($this->cache->get(CacheInterface::KEY_TORRENT_HASHES)[$hash]);
    }
}
