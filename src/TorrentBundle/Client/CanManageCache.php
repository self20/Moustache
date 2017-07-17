<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

interface CanManageCache
{
    /**
     * Can be very important for some RPC client (transmission) who have dynamic ids.
     */
    public function updateCache();
}
