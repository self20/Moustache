<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use StandardBundle\TorrentInterface;

interface TorrentLinkGeneratorInterface
{
    const RELATIVE_KERNEL_TO_WEB_ROUTE = '/../web';

    /**
     * @param TorrentInterface $torrent
     *
     * @return string
     */
    public function generateWebLink(TorrentInterface $torrent): string;

    /**
     * @param TorrentInterface $torrent
     *
     * @return string
     */
    public function generateAbsoluteLink(TorrentInterface $torrent): string;
}
