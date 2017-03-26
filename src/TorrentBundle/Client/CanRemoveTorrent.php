<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanRemoveTorrent
{
    /**
     * @param TorrentInterface $torrent
     */
    public function remove(TorrentInterface $torrent);

    /**
     * @param TorrentInterface $torrent
     */
    public function removeAndDeleteLocalData(TorrentInterface $torrent);
}
