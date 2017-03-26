<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter\Fake;

use TorrentBundle\Adapter\LifeCycleAdapterInterface;
use TorrentBundle\Entity\TorrentInterface;

class FakeLifeCycleAdapter implements LifeCycleAdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function startLater(TorrentInterface $torrent)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function startNow(TorrentInterface $torrent)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function stop(TorrentInterface $torrent)
    {
    }
}
