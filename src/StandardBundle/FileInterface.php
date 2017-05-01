<?php

declare(strict_types=1);

namespace StandardBundle;

interface FileInterface extends EntityInterface, CanBeBrowsed, CanBeIncomplete
{
    /**
     * @return TorrentInterface|null
     */
    public function getTorrent();

    /**
     * TorrentInterface $torrent.
     */
    public function setTorrent(TorrentInterface $torrent);
}
