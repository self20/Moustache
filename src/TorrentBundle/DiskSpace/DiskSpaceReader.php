<?php

declare(strict_types=1);

namespace TorrentBundle\DiskSpace;

use TorrentBundle\Client\CanGetTorrent;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Helper\HelperGetterInterface;

class DiskSpaceReader
{
    /**
     * @var HelperGetterInterface
     */
    private $torrentStorageHelper;

    /**
     * @var CanGetTorrent
     */
    private $client;

    /**
     * @param HelperGetterInterface $torrentStorageHelper
     * @param CanGetTorrent         $client
     */
    public function __construct(HelperGetterInterface $torrentStorageHelper, CanGetTorrent $client)
    {
        $this->torrentStorageHelper = $torrentStorageHelper;
        $this->client = $client;
    }

    /**
     * @return int
     */
    public function getTotalSpace(): int
    {
        return (int) disk_total_space($this->torrentStorageHelper->get());
    }

    /**
     * @return int
     */
    public function getFreeSpace(): int
    {
        return (int) disk_free_space($this->torrentStorageHelper->get());
    }

    /**
     * @return int
     */
    public function getUsedSpace(): int
    {
        return $this->getTotalSpace() - $this->getFreeSpace();
    }

    /**
     * @return int
     */
    public function getVirtualFreeSpace(): int
    {
        return $this->getFreeSpace() - $this->getVirtualUsedSpace();
    }

    /**
     * @return int
     */
    public function getVirtualUsedSpace(): int
    {
        return array_sum(array_map(function (TorrentInterface $torrent) {
            if ($torrent->isStarted()) {
                return $torrent->getVirtualUsedByteSize();
            }
        }, $this->client->getAll()));
    }
}
