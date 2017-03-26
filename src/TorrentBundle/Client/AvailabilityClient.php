<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Adapter\AdapterInterface;

class AvailabilityClient implements CanBeAvailable
{
    /**
     * @var AdapterInterface
     */
    private $externalClient;

    public function __construct(AdapterInterface $externalClient)
    {
        $this->externalClient = $externalClient;
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return $this->externalClient->isAvailable();
    }
}
