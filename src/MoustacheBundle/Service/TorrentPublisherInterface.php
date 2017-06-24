<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use MoustacheBundle\Exception\Permission\DownloadPermissionException;
use StandardBundle\TorrentInterface;

interface TorrentPublisherInterface
{
    /**
     * @param TorrentInterface $torrent
     *
     * @throws DownloadPermissionException
     *
     * @return string
     */
    public function publish(TorrentInterface $torrent): string;
}
