<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Client\TorrentAdapterException;
use TorrentBundle\Exception\Torrent\CannotFillTorrentException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;
use TorrentBundle\Filter\TorrentFilterInterface;
use TorrentBundle\Helper\TorrentStorageHelper;
use TorrentBundle\Mapper\TorrentMapperInterface;

class AccessorClient implements AccessorClientInterface
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
     * @var ExternalTorrentGetter
     */
    private $externalTorrentGetter;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param AdapterInterface         $externalClient
     * @param TorrentMapperInterface   $torrentMapper
     * @param TorrentStorageHelper     $torrentStorageHelper
     * @param TorrentFilterInterface   $torrentFilter
     * @param ExternalTorrentGetter    $externalTorrentGetter
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, AdapterInterface $externalClient, TorrentMapperInterface $torrentMapper, TorrentStorageHelper $torrentStorageHelper, TorrentFilterInterface $torrentFilter, ExternalTorrentGetter $externalTorrentGetter)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->externalClient = $externalClient;
        $this->torrentMapper = $torrentMapper;
        $this->torrentStorageHelper = $torrentStorageHelper;
        $this->torrentFilter = $torrentFilter;
        $this->externalTorrentGetter = $externalTorrentGetter;
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
        return $this->getAndMapAndDispatchEvent($this->getAuthenticatedUserTorrent($id));
    }

    /**
     * {@inheritdoc}
     *
     * @throws CannotFillTorrentException
     */
    public function getAll(): array
    {
        return array_filter(array_map(function ($notMappedTorrent) {
            $externalTorrent = $this->externalTorrentGetter->get($notMappedTorrent->getHash());

            if (null !== $externalTorrent) {
                $torrent = $this->doMapTorrent($notMappedTorrent, $externalTorrent);
                $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_GET, new TorrentAfterEvent($torrent));

                return $torrent;
            }
        }, $this->torrentFilter->getAllAuthenticatedUserTorrents()));
    }

    private function getAndMapAndDispatchEvent(TorrentInterface $notMappedTorrent): TorrentInterface
    {
        $torrent = $this->doMapTorrent($notMappedTorrent, $this->externalTorrentGetter->get($notMappedTorrent->getHash()));

        $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_GET, new TorrentAfterEvent($torrent));

        return $torrent;
    }

    private function getAuthenticatedUserTorrent(int $id): TorrentInterface
    {
        $notMappedTorrent = $this->torrentFilter->getAuthenticatedUserTorrent($id);

        if (null === $notMappedTorrent) {
            throw new TorrentNotFoundException($id);
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
