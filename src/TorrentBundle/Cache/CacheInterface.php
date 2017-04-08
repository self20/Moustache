<?php

declare(strict_types=1);

namespace TorrentBundle\Cache;

interface CacheInterface
{
    const KEY_TORRENT_HASHES = 'hashes_external_id';
    const KEY_CLIENT_TOKEN = 'client_token';

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @return bool
     */
    public function isUpToDate(): bool;

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value);
}
