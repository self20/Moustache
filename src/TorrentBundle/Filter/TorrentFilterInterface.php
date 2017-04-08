<?php

declare(strict_types=1);

namespace TorrentBundle\Filter;

interface TorrentFilterInterface
{
    /**
     * @return Torrent[]
     */
    public function getAllAuthenticatedUserTorrents();

    /**
     * @param int $torrentId
     *
     * @return Torrent|null
     */
    public function getAuthenticatedUserTorrent(int $torrentId);
}
