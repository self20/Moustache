<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanStartTorrent
{
    /**
     * @param TorrentInterface $torrent
     */
    public function start(TorrentInterface $torrent);

    /**
     * @param TorrentInterface $torrent
     */
    public function startWithoutLimits(TorrentInterface $torrent);
}
