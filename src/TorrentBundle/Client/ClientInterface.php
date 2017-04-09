<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

interface ClientInterface
{
    const FAKE = 'fake';
    const TRANSMISSION = 'transmission';

    /**
     * @return bool
     */
    public function isAvailable(): bool;

    // @HEYLISTEN Fill this interface
}
