<?php

namespace TorrentBundle\Mapper;

use TorrentBundle\Entity\TorrentInterface;

class FakeTorrentMapper implements TorrentMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function map(TorrentInterface $torrent, $externalTorrent): TorrentInterface
    {
        return $externalTorrent;
    }

    /**
     * {@inheritdoc}
     */
    public function mapFiles(TorrentInterface $torrent, $externalTorrent): TorrentInterface
    {
        return $externalTorrent;
    }
}
