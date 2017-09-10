<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Exception\Client\TorrentAdapterException;
use TorrentBundle\Exception\Torrent\CannotFillTorrentException;
use TorrentBundle\Helper\TorrentStorageHelper;
use TorrentBundle\Mapper\TorrentMapperInterface;

class AddClient implements AddClientInterface
{
    use MapperTrait;

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
     * @param EventDispatcherInterface $eventDispatcher
     * @param AdapterInterface         $externalClient
     * @param TorrentMapperInterface   $torrentMapper
     * @param TorrentStorageHelper     $torrentStorageHelper
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, AdapterInterface $externalClient, TorrentMapperInterface $torrentMapper, TorrentStorageHelper $torrentStorageHelper)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->externalClient = $externalClient;
        $this->torrentMapper = $torrentMapper;
        $this->torrentStorageHelper = $torrentStorageHelper;
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

        return $this->mapAndDispatchEvent($torrent, $externalTorrent, Events::AFTER_TORRENT_ADDED);
    }

    private function doAddTorrent(TorrentInterface $torrent, string $savePath = null)
    {
        try {
            return $this->externalClient->add($torrent, $savePath);
        } catch (\Exception $ex) {
            throw new TorrentAdapterException($ex->getMessage());
        }
    }
}
