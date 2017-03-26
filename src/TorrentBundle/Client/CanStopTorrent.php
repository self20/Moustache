<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanStopTorrent
{
    /**
     * @param TorrentInterface $torrent
     */
    public function stop(TorrentInterface $torrent);
}
