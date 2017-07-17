<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use StandardBundle\CanDownload;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Torrent\CannotStartTorrentException;
use TorrentBundle\Exception\Torrent\CannotStopTorrentException;

class LifeCycleClient implements LifeCycleClientInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var AdapterInterface
     */
    private $externalClient;

    /**
     * @var ExternalTorrentGetter
     */
    private $externalTorrentGetter;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param AdapterInterface         $externalClient
     * @param ExternalTorrentGetter    $externalTorrentGetter
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, AdapterInterface $externalClient, ExternalTorrentGetter $externalTorrentGetter)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->externalClient = $externalClient;
        $this->externalTorrentGetter = $externalTorrentGetter;
    }

    /**
     * {@inheritdoc}
     */
    public function start(TorrentInterface $torrent)
    {
        $this->doStartTorrent($torrent, $this->externalTorrentGetter->get($torrent->getHash()));

        $torrent->setStatus(CanDownload::STATUS_DOWNLOADING);
    }

    /**
     * {@inheritdoc}
     */
    public function startWithoutLimits(TorrentInterface $torrent)
    {
        $this->start($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function stop(TorrentInterface $torrent)
    {
        $this->doStopTorrent($torrent, $this->externalTorrentGetter->get($torrent->getHash()));

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
            $this->externalClient->start($externalTorrent);
        } catch (\Exception $ex) {
            throw new CannotStartTorrentException(sprintf('Oops, it seems “%s” torrent cannot be started.', $torrent->getFriendlyName()), 0, $ex);
        }

        $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_STARTED, new TorrentAfterEvent($torrent));
    }
}
