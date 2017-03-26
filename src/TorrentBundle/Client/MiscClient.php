<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Entity\TorrentInterface;

class MiscClient implements MiscClientInterface
{
    /**
     * @var AdapterInterface
     */
    private $externalClient;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param AdapterInterface $externalClient
     * @param CacheInterface   $cache
     */
    public function __construct(AdapterInterface $externalClient, CacheInterface $cache)
    {
        $this->externalClient = $externalClient;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getSessionToken(): string
    {
        return $this->externalClient->getSessionToken();
    }

    /**
     * {@inheritdoc}
     */
    public function reannounce(TorrentInterface $torrent)
    {
        $this->externalClient->reannounce($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function updateCache()
    {
        $this->cache->set(CacheInterface::KEY_TORRENT_HASHES, $this->externalClient->getCacheValues());
    }

    /**
     * {@inheritdoc}
     */
    public function verify(TorrentInterface $torrent)
    {
        $this->externalClient->verify($torrent);
    }
}
