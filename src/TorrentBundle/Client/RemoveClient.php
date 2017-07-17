<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Torrent\CannotRemoveTorrentException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;

class RemoveClient implements RemoveClientInterface
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
     *
     * @throws TorrentNotFoundException
     */
    public function remove(TorrentInterface $torrent)
    {
        $this->doRemoveTorrent($torrent, $this->externalTorrentGetter->get($torrent->getHash()), $withLocalData = false);
    }

    /**
     * {@inheritdoc}
     *
     * @throws TorrentNotFoundException
     */
    public function removeAndDeleteLocalData(TorrentInterface $torrent)
    {
        $this->doRemoveTorrent($torrent, $this->externalTorrentGetter->get($torrent->getHash()), $withLocalData = true);
    }

    private function doRemoveTorrent(TorrentInterface $torrent, $externalTorrent, bool $withLocalData = true)
    {
        try {
            $this->externalClient->remove($externalTorrent, $withLocalData);
        } catch (\Exception $ex) {
            throw new CannotRemoveTorrentException(sprintf('Oops, an error occured when trying to remove “%s” torrent.', $torrent->getFriendlyName()), 0, $ex);
        }

        $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_REMOVED, new TorrentAfterEvent($torrent));
    }
}
