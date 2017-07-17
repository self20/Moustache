<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

interface ClientInterface extends MiscClientInterface, CanStartTorrent, CanStopTorrent, CanAddTorrent, CanBeAvailable, CanGetTorrent, CanRemoveTorrent
{
    const FAKE = 'fake';
    const TRANSMISSION = 'transmission';

    /**
     * @return string
     */
    public function getName(): string;
}
