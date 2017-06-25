<?php

declare(strict_types=1);

namespace TorrentBundle\Cache;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Cache implements CacheInterface
{
    const MAX_REFRESH_TIME = 15;

    /**
     * @var AbstractAdapter
     */
    private $cacheAdapter;

    /**
     * @var string
     */
    private $rpcClient;

    /**
     * @param string $rpcClient
     */
    public function __construct(string $rpcClient)
    {
        $this->cacheAdapter = new FilesystemAdapter();
        $this->rpcClient = $rpcClient;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key)
    {
        return $this->cacheAdapter->getItem($this->getKey($key))->get();
    }

    /**
     * {@inheritdoc}
     */
    public function isUpToDate(): bool
    {
        return (time() - $this->getLatestUpdateTime()) < self::MAX_REFRESH_TIME;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value)
    {
        $cacheItem = $this->cacheAdapter->getItem($this->getKey($key));
        $cacheItem->set($value);

        $this->cacheAdapter->save($cacheItem);

        $this->saveLatestUpdateTime();
    }

    private function getKey(string $key): string
    {
        return $this->rpcClient.'.'.$key;
    }

    private function saveLatestUpdateTime()
    {
        $timeCacheItem = $this->cacheAdapter->getItem($this->getKey('latestUpdate'));
        $timeCacheItem->set(time());

        $this->cacheAdapter->save($timeCacheItem);
    }

    private function getLatestUpdateTime()
    {
        return $this->cacheAdapter->getItem($this->getKey('latestUpdate'))->get();
    }
}
