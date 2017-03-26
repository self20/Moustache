<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter\Fake;

use TorrentBundle\Adapter\AccessorAdapterInterface;
use TorrentBundle\DataFixtures\Data\TorrentData;
use TorrentBundle\Entity\TorrentInterface;

class FakeAccessorAdapter implements AccessorAdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function add(\SplFileInfo $torrentFile, string $savepath = null): TorrentInterface
    {
    }

    /**
     * {@inheritdoc}
     */
    public function fill(TorrentInterface $torrent): TorrentInterface
    {
        TorrentData::createAll();

        return TorrentData::$torrents[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function fillAll(array $torrents): array
    {
        TorrentData::createAll();

        return TorrentData::$torrents;
    }
}
