<?php

namespace TorrentBundle\Mapper;

use TorrentBundle\Entity\TorrentInterface;

interface TorrentMapperInterface
{
    /**
     * @param TorrentInterface $torrent
     * @param type             $externalTorrent
     *
     * @return TorrentInterface
     */
    public function map(TorrentInterface $torrent, $externalTorrent): TorrentInterface;

    /**
     * @param TorrentInterface $torrent
     * @param mixed            $externalTorrent
     *
     * @return TorrentInterface
     */
    public function mapFiles(TorrentInterface $torrent, $externalTorrent): TorrentInterface;
}
