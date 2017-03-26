<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanAddTorrent
{
    /**
     * @param string $torrentFile
     * @param string $savepath
     *
     * @return TorrentInterface
     */
    public function add(\SplFileInfo $torrentFile): TorrentInterface;
}
