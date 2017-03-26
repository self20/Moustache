<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

interface FileInterface extends EntityInterface, CanBeBrowsed, CanDownload
{
    /**
     * @return string
     */
    public function getFullPath(): string;

    /**
     * @return TorrentInterface|null
     */
    public function getTorrent();

    /**
     * TorrentInterface $torrent.
     */
    public function setTorrent(TorrentInterface $torrent);
}
