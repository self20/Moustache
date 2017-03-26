<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use Psr\Log\LoggerInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\TorrentAfterEvent;

/**
 * Fills the outdated cache with fresh data.
 */
final class CacheUpdaterListener
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ClientInterface $client
     * @param LoggerInterface $logger
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param TorrentAfterEvent $event
     */
    public function afterTorrentAdded(TorrentAfterEvent $event)
    {
        $this->client->updateCache();
        $this->logger->info(sprintf('Cache updated for client “%s” after adding a new torrent.', $this->client->getName()));

        return $event;
    }

    /**
     * @param TorrentAfterEvent $event
     */
    public function afterTorrentRemoved(TorrentAfterEvent $event)
    {
        $this->client->updateCache();
        $this->logger->info(sprintf('Cache updated for client “%s” after removing a torrent.', $this->client->getName()));

        return $event;
    }
}
