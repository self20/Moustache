<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanVerifyTorrent
{
    /**
     * @param TorrentInterface $torrent
     */
    public function verify(TorrentInterface $torrent);
}
