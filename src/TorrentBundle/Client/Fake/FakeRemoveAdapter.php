<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter\Fake;

use TorrentBundle\Adapter\RemoveAdapterInterface;
use TorrentBundle\Entity\TorrentInterface;

class FakeRemoveAdapter implements RemoveAdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function remove(TorrentInterface $torrent)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function removeAndDeleteLocalData(TorrentInterface $torrent)
    {
    }
}
