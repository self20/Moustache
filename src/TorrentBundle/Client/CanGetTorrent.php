<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface CanGetTorrent
{
    /**
     * @param int $id
     *
     * @return TorrentInterface
     */
    public function get(int $id): TorrentInterface;

    /**
     * @return TorrentInterface[]
     */
    public function getAll(): array;
}
