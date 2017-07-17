<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

interface MiscClientInterface extends CanAnnounceTorrent, CanVerifyTorrent, CanManageCache
{
    /**
     * @return string
     */
    public function getSessionToken(): string;
}
