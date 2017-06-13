<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter;

use TorrentBundle\Entity\TorrentInterface;

interface AdapterInterface
{
    /**
     * @param string $torrent
     * @param string $savePath
     *
     * @return mixed
     */
    public function add(TorrentInterface $torrent, string $savePath = null);

    /**
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * @param mixed $id
     *
     * @return mixed
     */
    public function get($id);

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @return mixed[]
     */
    public function getCacheValues(): array;

    /**
     * @return string
     */
    public function getSessionToken(): string;

    /**
     * @param mixed $torrent
     * @param bool  $withLocalData
     */
    public function remove($torrent, $withLocalData);

    /**
     * @param mixed $torrent
     */
    public function startLater($torrent);

    /**
     * @param mixed $torrent
     */
    public function startNow($torrent);

    /**
     * @param mixed $torrent
     */
    public function stop($torrent);
}
