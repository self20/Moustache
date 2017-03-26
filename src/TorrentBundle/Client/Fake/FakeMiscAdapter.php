<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter\Fake;

use TorrentBundle\Adapter\MiscAdapterInterface;
use TorrentBundle\Entity\TorrentInterface;

class FakeMiscAdapter implements MiscAdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function reannounce(TorrentInterface $torrent)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function verify(TorrentInterface $torrent)
    {
    }
}
