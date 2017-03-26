<?php

declare(strict_types=1);

namespace TorrentBundle\Cache;

use Symfony\Component\Cache\Adapter\AbstractAdapter;

class Cache implements CacheInterface
{
    /**
     * @var AbstractAdapter
     */
    private $cacheAdapter;

    /**
     * @var string
     */
    private $rpcClient;

    /**
     * @param AbstractAdapter $cacheAdapter
     * @param string          $rpcClient
     */
    public function __construct(AbstractAdapter $cacheAdapter, string $rpcClient)
    {
        $this->cacheAdapter = $cacheAdapter;
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
    public function set(string $key, $value)
    {
        $cacheItem = $this->cacheAdapter->getItem($this->getKey($key));
        $cacheItem->set($value);

        $this->cacheAdapter->save($cacheItem);
    }

    private function getKey(string $key): string
    {
        return $this->rpcClient.'.'.$key;
    }
}
