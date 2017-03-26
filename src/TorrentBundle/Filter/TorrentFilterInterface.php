<?php

declare(strict_types=1);

namespace TorrentBundle\Filter;

use TorrentBundle\Entity\TorrentInterface;

interface TorrentFilterInterface
{
    /**
     * @param TorrentInterface $torrents
     *
     * @return TorrentInterface[]
     */
    public function filterAuthenticatedUserTorrentsOnly(array $torrents): array;

    /**
     * @param TorrentInterface $torrent
     * @param string[]         $authenticatedUserTorrentHashes
     *
     * @return bool
     */
    public function authenticatedUserOwns(TorrentInterface $torrent): bool;

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
