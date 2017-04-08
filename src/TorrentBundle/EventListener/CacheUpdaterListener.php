<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\CacheOutdatedException;

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
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ClientInterface $client
     * @param CacheInterface  $cache
     * @param LoggerInterface $logger
     */
    public function __construct(ClientInterface $client, CacheInterface $cache, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param TorrentAfterEvent $event
     *
     * @return $event|null
     */
    public function afterTorrentAdded(TorrentAfterEvent $event)
    {
        if ($this->shouldUpdate()) {
            return;
        }

        $this->client->updateCache();
        $this->logger->info(sprintf('Cache updated for client “%s” after adding a new torrent.', $this->client->getName()));

        return $event;
    }

    /**
     * @param TorrentAfterEvent $event
     *
     * @return $event|null
     */
    public function afterTorrentRemoved(TorrentAfterEvent $event)
    {
        if ($this->shouldUpdate()) {
            return;
        }

        $this->client->updateCache();
        $this->logger->info(sprintf('Cache updated for client “%s” after removing a torrent.', $this->client->getName()));

        return $event;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @return $event|null
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$this->shouldUpdate() || !$event->getException() instanceof CacheOutdatedException) {
            return;
        }

        $this->client->updateCache();
        $this->logger->warning(sprintf('Cache updated for client “%s” after an exception occured.', $this->client->getName()), ['exception' => $event->getException()]);

        return $event;
    }

    private function shouldUpdate(): bool
    {
        return !$this->cache->isUpToDate();
    }
}
