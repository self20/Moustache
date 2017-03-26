<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

interface MiscClientInterface extends CanAnnounceTorrent, CanVerifyTorrent, CanManageCache
{
    /**
     * @return string
     */
    public function getSessionToken(): string;

    /**
     * @param TorrentInterface $torrent
     */
    public function reannounce(TorrentInterface $torrent);

    /**
     * Can be very important for some RPC client (transmission) who have dynamic ids.
     */
    public function updateCache();

    /**
     * @param TorrentInterface $torrent
     */
    public function verify(TorrentInterface $torrent);
}
