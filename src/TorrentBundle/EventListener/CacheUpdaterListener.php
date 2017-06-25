<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Client\CacheOutdatedException;
use TorrentBundle\Helper\TorrentClientHelper;

/**
 * Fills the outdated cache with fresh data.
 */
final class CacheUpdaterListener
{
    /**
     * @var TorrentClientHelper
     */
    private $torrentClientHelper;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param TorrentClientHelper $torrentClientHelper
     * @param CacheInterface      $cache
     * @param LoggerInterface     $logger
     */
    public function __construct(TorrentClientHelper $torrentClientHelper, CacheInterface $cache, LoggerInterface $logger)
    {
        $this->torrentClientHelper = $torrentClientHelper;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param TorrentAfterEvent $event
     *
     * @return TorrentAfterEvent|null
     */
    public function afterTorrentAdded(TorrentAfterEvent $event)
    {
        if ($this->shouldUpdate()) {
            return;
        }

        $this->torrentClientHelper->get()->updateCache();
        $this->logger->info(sprintf('Cache updated for client “%s” after adding a new torrent.', $this->torrentClientHelper->get()->getName()));

        return $event;
    }

    /**
     * @param TorrentAfterEvent $event
     *
     * @return TorrentAfterEvent|null
     */
    public function afterTorrentRemoved(TorrentAfterEvent $event)
    {
        if ($this->shouldUpdate()) {
            return;
        }

        $this->torrentClientHelper->get()->updateCache();
        $this->logger->info(sprintf('Cache updated for client “%s” after removing a torrent.', $this->torrentClientHelper->get()->getName()));

        return $event;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @return TorrentAfterEvent|null
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof CacheOutdatedException || !$this->shouldUpdate()) {
            return;
        }

        $this->torrentClientHelper->get()->updateCache();
        $this->logger->warning(sprintf('Cache updated for client “%s” after an exception occured.', $this->torrentClientHelper->get()->getName()), ['exception' => $event->getException()]);

        return $event;
    }

    private function shouldUpdate(): bool
    {
        return
            !$this->torrentClientHelper->isEmpty() &&
            !$this->cache->isUpToDate()
        ;
    }
}
