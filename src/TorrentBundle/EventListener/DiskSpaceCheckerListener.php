<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use Psr\Log\LoggerInterface;
use Rico\Lib\StringUtils;
use TorrentBundle\Client\CanStopTorrent;
use TorrentBundle\DiskSpace\DiskSpaceChecker;
use TorrentBundle\DiskSpace\DiskSpaceReader;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Permission\NotEnoughDiskSpaceException;
use TorrentBundle\Helper\HelperGetterInterface;

/**
 * Stops a torrent and throws an exception when virtual disk space is not sufficient for a started torrent.
 */
final class DiskSpaceCheckerListener
{
    /**
     * @var DiskSpaceChecker
     */
    private $diskSpaceChecker;

    /**
     * @var DiskSpaceReader
     */
    private $diskSpaceReader;

    /**
     * @var CanStopTorrent
     */
    private $stopClient;

    /**
     * @var HelperGetterInterface
     */
    private $torrentStorageHelper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * @param DiskSpaceChecker      $diskSpaceChecker
     * @param DiskSpaceReader       $diskSpaceReader
     * @param CanStopTorrent        $stopClient
     * @param HelperGetterInterface $torrentStorageHelper
     * @param LoggerInterface       $logger
     * @param StringUtils           $stringUtils
     */
    public function __construct(DiskSpaceChecker $diskSpaceChecker, DiskSpaceReader $diskSpaceReader, CanStopTorrent $stopClient, HelperGetterInterface $torrentStorageHelper, LoggerInterface $logger, StringUtils $stringUtils)
    {
        $this->diskSpaceChecker = $diskSpaceChecker;
        $this->diskSpaceReader = $diskSpaceReader;
        $this->stopClient = $stopClient;
        $this->torrentStorageHelper = $torrentStorageHelper;
        $this->logger = $logger;
        $this->stringUtils = $stringUtils;
    }

    /**
     * @param TorrentAfterEvent $event
     *
     * @throws NotEnoughDiskSpaceException
     */
    public function afterTorrentStarted(TorrentAfterEvent $event)
    {
        if ($this->diskSpaceChecker->checkEnoughDiskSpace()) {
            return;
        }

        $this->stopClient->stop($event->getTorrent());

        $humanVirtualNeededSpace = $this->stringUtils->humanFilesize($event->getTorrent()->getVirtualUsedByteSize());
        $humanVirtualAvailableSpace = $this->stringUtils->humanFilesize($this->diskSpaceReader->getVirtualFreeSpace());

        $exception = new NotEnoughDiskSpaceException(sprintf('Torrent %s cannot be started because disk space is lacking.', $event->getTorrent()->getFriendlyName()));
        $exception->setNeededSpace($humanVirtualNeededSpace);
        $exception->setAvailableSpace($humanVirtualAvailableSpace);

        $this->logger->warning('Failed to start a torrent.', [
            'exception' => $exception, 'storage' => $this->torrentStorageHelper->get(), 'needed' => $humanVirtualNeededSpace, 'available' => $humanVirtualAvailableSpace,
        ]);

        throw $exception;
    }
}
