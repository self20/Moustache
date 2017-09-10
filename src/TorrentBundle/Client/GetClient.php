<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Exception\Torrent\CannotFillTorrentException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;
use TorrentBundle\Filter\TorrentFilterInterface;
use TorrentBundle\Mapper\TorrentMapperInterface;

class GetClient implements GetClientInterface
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
     * @param TorrentFilterInterface   $torrentFilter
     * @param ExternalTorrentGetter    $externalTorrentGetter
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, AdapterInterface $externalClient, TorrentMapperInterface $torrentMapper, TorrentFilterInterface $torrentFilter, ExternalTorrentGetter $externalTorrentGetter)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->externalClient = $externalClient;
        $this->torrentMapper = $torrentMapper;
        $this->torrentFilter = $torrentFilter;
        $this->externalTorrentGetter = $externalTorrentGetter;
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

        return $this->mapAndDispatchEvent($notMappedTorrent, $this->externalTorrentGetter->get($notMappedTorrent->getHash()), Events::AFTER_TORRENT_GET);
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
                return $this->mapAndDispatchEvent($notMappedTorrent, $externalTorrent, Events::AFTER_TORRENT_GET);
            }
        }, $this->torrentFilter->getAllAuthenticatedUserTorrents()));
    }

    private function getAuthenticatedUserTorrent(int $id): TorrentInterface
    {
        $notMappedTorrent = $this->torrentFilter->getAuthenticatedUserTorrent($id);

        if (null === $notMappedTorrent) {
            throw new TorrentNotFoundException($id);
        }

        return $notMappedTorrent;
    }
}
