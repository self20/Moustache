<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Cache\CacheInterface;
use TorrentBundle\Client\Traits\ExternalTorrentGetterTrait;
use TorrentBundle\Entity\Torrent;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\CannotFillTorrentException;
use TorrentBundle\Exception\TorrentAdapterException;
use TorrentBundle\Exception\TorrentNotFoundException;
use TorrentBundle\Filter\TorrentFilterInterface;
use TorrentBundle\Helper\TorrentStorageHelper;
use TorrentBundle\Mapper\TorrentMapperInterface;

class AccessorClient implements AccessorClientInterface
{
    use ExternalTorrentGetterTrait;

    /**
     * @var AdapterInterface
     */
    private $externalClient;

    /**
     * @var TorrentMapperInterface
     */
    private $torrentMapper;

    /**
     * @var TorrentStorageHelper
     */
    private $torrentStorageHelper;

    /**
     * @var TorrentFilterInterface
     */
    private $torrentFilter;

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
     * @param TorrentMapperInterface   $torrentMapper
     * @param TorrentStorageHelper     $torrentStorageHelper
     * @param TorrentFilterInterface   $torrentFilter
     * @param EventDispatcherInterface $eventDispatcher
     * @param CacheInterface           $cache
     */
    public function __construct(AdapterInterface $externalClient, TorrentMapperInterface $torrentMapper, TorrentStorageHelper $torrentStorageHelper, TorrentFilterInterface $torrentFilter, EventDispatcherInterface $eventDispatcher, CacheInterface $cache)
    {
        $this->externalClient = $externalClient;
        $this->torrentMapper = $torrentMapper;
        $this->torrentStorageHelper = $torrentStorageHelper;
        $this->torrentFilter = $torrentFilter;
        $this->eventDispatcher = $eventDispatcher;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     *
     * @throws TorrentAdapterException
     * @throws CannotFillTorrentException
     */
    public function add(TorrentInterface $torrent): TorrentInterface
    {
        $externalTorrent = $this->doAddTorrent($torrent, $this->torrentStorageHelper->get());

        $torrent = $this->doMapTorrent($torrent, $externalTorrent);

        $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_ADDED, new TorrentAfterEvent($torrent));

        return $torrent;
    }

    /**
     * {@inheritdoc}
     *
     * @throws TorrentNotFoundException
     * @throws CannotFillTorrentException
     **/
    public function get(int $id): TorrentInterface
    {
        $notMappedTorrent = $this->getAuthenticatedUserTorrent($id);

        return $this->getAndMapAndDispatchEvent($notMappedTorrent);
    }

    /**
     * {@inheritdoc}
     *
     * @throws TorrentNotFoundException
     * @throws CannotFillTorrentException
     */
    public function getAll(): array
    {
        $notMappedTorrents = $this->torrentFilter->getAllAuthenticatedUserTorrents();

        return array_map(function ($notMappedTorrent) {
            return $this->getAndMapAndDispatchEvent($notMappedTorrent);
        }, $notMappedTorrents);
    }

    private function getAndMapAndDispatchEvent(TorrentInterface $notMappedTorrent): TorrentInterface
    {
        $externalTorrent = $this->getExternalTorrent($notMappedTorrent->getHash());

        $torrent = $this->doMapTorrent($notMappedTorrent, $externalTorrent);

        $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_GET, new TorrentAfterEvent($torrent));

        return $torrent;
    }

    private function getAuthenticatedUserTorrent(int $id): TorrentInterface
    {
        $notMappedTorrent = $this->torrentFilter->getAuthenticatedUserTorrent($id);

        if (null === $notMappedTorrent) {
            // @HEYLISTEN All the not found exception should update the cache (remove the incriminated entry).
            throw new TorrentNotFoundException(sprintf('A torrent with id “%s” was requested but it does not exist.', $id));
        }

        return $notMappedTorrent;
    }

    private function doAddTorrent(TorrentInterface $torrent, string $savePath = null)
    {
        try {
            return $this->externalClient->add($torrent, $savePath);
        } catch (\Exception $ex) {
            throw new TorrentAdapterException($ex->getMessage());
        }
    }

    private function doMapTorrent(TorrentInterface $torrent, $externalTorrent): TorrentInterface
    {
        try {
            $partialTorrent = $this->torrentMapper->map($torrent, $externalTorrent);

            return $this->torrentMapper->mapFiles($partialTorrent, $externalTorrent);
        } catch (\Exception $ex) {
            throw new CannotFillTorrentException(sprintf('The torrent with id “%s” cannot be filled with data.', $torrent->getHash()), 0, $ex);
        }
    }
}
