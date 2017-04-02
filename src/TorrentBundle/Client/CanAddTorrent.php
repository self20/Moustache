<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanAddTorrent
{
    /**
     * @param TorrentInterface $torrent
     *
     * @return TorrentInterface
     */
    public function add(TorrentInterface $torrent): TorrentInterface;
}
