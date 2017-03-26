<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanAnnounceTorrent
{
    /**
     * @param TorrentInterface $torrent
     */
    public function reannounce(TorrentInterface $torrent);
}
