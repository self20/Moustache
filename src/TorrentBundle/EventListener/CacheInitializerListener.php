<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use Psr\Log\LoggerInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\ClientAfterEvent;

/**
 * Fills cache with new data after a client is retrieved.
 */
final class CacheInitializerListener
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param CacheInterface  $cache
     * @param LoggerInterface $logger
     */
    public function __construct(CacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param ClientAfterEvent $event
     */
    public function afterClientRetrieved(ClientAfterEvent $event)
    {
        $client = $event->getClient();

        if (!$this->isCacheOutdated($client)) {
            return;
        }

        $client->updateCache();
        $this->saveClientToken($client->getSessionToken());

        $this->logger->info(sprintf('Cache for client “%s” was initialized.', $client->getName()));

        return $event;
    }

    private function isCacheOutdated(ClientInterface $client): bool
    {
        $clientToken = $this->cache->get(CacheInterface::KEY_CLIENT_TOKEN);

        return
            empty($clientToken) ||
            $client->getSessionToken() !== $clientToken
        ;
    }

    public function saveClientToken(string $clientToken)
    {
        $this->cache->set(CacheInterface::KEY_CLIENT_TOKEN, $clientToken);
    }
}
