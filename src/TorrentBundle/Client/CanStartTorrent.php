<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanStartTorrent
{
    /**
     * @param TorrentInterface $torrent
     */
    public function startLater(TorrentInterface $torrent);

    /**
     * @param TorrentInterface $torrent
     */
    public function startNow(TorrentInterface $torrent);
}
