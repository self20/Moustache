<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Client\Traits\ExternalTorrentGetterTrait;
use TorrentBundle\Entity\CanDownload;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\CannotStopTorrentException;

class LifeCycleClient implements LifeCycleClientInterface
{
    use ExternalTorrentGetterTrait;

    /**
     * @var AdapterInterface
     */
    private $externalClient;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param AdapterInterface         $externalClient
     * @param EventDispatcherInterface $eventDispatcher
     * @param CacheInterface           $cache
     */
    public function __construct(AdapterInterface $externalClient, EventDispatcherInterface $eventDispatcher, CacheInterface $cache)
    {
        $this->externalClient = $externalClient;
        $this->eventDispatcher = $eventDispatcher;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function startLater(TorrentInterface $torrent)
    {
        $this->externalClient->start($torrent, $now = false);
    }

    /**
     * {@inheritdoc}
     */
    public function startNow(TorrentInterface $torrent)
    {
        $this->doStartTorrent($torrent, $this->getExternalTorrent($torrent->getHash()));

        $torrent->setStatus(CanDownload::STATUS_DOWNLOADING);
    }

    /**
     * {@inheritdoc}
     */
    public function stop(TorrentInterface $torrent)
    {
        $this->doStopTorrent($torrent, $this->getExternalTorrent($torrent->getHash()));

        $torrent->setStatus(CanDownload::STATUS_STOP);
    }

    private function doStopTorrent(TorrentInterface $torrent, $externalTorrent)
    {
        try {
            $this->externalClient->stop($externalTorrent);
        } catch (\Exception $ex) {
            throw new CannotStopTorrentException(sprintf('Oops, an error occured when trying to stop “%s” torrent.', $torrent->getFriendlyName()), 0, $ex);
        }

        $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_STOPPED, new TorrentAfterEvent($torrent));
    }

    private function doStartTorrent(TorrentInterface $torrent, $externalTorrent)
    {
        try {
            $this->externalClient->startNow($externalTorrent);
        } catch (\Exception $ex) {
            throw new CannotStopTorrentException(sprintf('Oops, it seems “%s” torrent cannot be started.', $torrent->getFriendlyName()), 0, $ex);
        }

        $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_STARTED, new TorrentAfterEvent($torrent));
    }
}
