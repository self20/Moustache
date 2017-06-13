<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Client\Traits\ExternalTorrentGetterTrait;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Torrent\CannotRemoveTorrentException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;

class RemoveClient implements RemoveClientInterface
{
    use ExternalTorrentGetterTrait;

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
     *
     * @throws TorrentNotFoundException
     */
    public function remove(TorrentInterface $torrent)
    {
        $this->doRemoveTorrent($torrent, $this->getExternalTorrent($torrent->getHash()), $localData = false);
    }

    /**
     * {@inheritdoc}
     *
     * @throws TorrentNotFoundException
     */
    public function removeAndDeleteLocalData(TorrentInterface $torrent)
    {
        $this->doRemoveTorrent($torrent, $this->getExternalTorrent($torrent->getHash()), $localData = true);
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
