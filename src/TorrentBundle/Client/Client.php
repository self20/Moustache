<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

class Client implements ClientInterface
{
    /**
     * @var AccessorClientInterface
     */
    private $accessorClient;

    /**
     * @var LifeCycleClientInterface
     */
    private $lifeCycleClient;

    /**
     * @var MiscClientInterface
     */
    private $miscClient;

    /**
     * @var RemoveClientInterface
     */
    private $removeClient;

    /**
     * @var CanBeAvailable
     */
    private $availabilityClient;

    /**
     * @var string
     */
    private $name;

    /**
     * @param AccessorClientInterface  $accessorClient
     * @param LifeCycleClientInterface $lifeCycleClient
     * @param MiscClientInterface      $miscClient
     * @param RemoveClientInterface    $removeClient
     * @param CanBeAvailable           $availabilityClient
     * @param string                   $name
     */
    public function __construct(AccessorClientInterface $accessorClient, LifeCycleClientInterface $lifeCycleClient, MiscClientInterface $miscClient, RemoveClientInterface $removeClient, CanBeAvailable $availabilityClient, string $name)
    {
        $this->accessorClient = $accessorClient;
        $this->lifeCycleClient = $lifeCycleClient;
        $this->miscClient = $miscClient;
        $this->removeClient = $removeClient;
        $this->availabilityClient = $availabilityClient;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function add(\SplFileInfo $torrentFile): TorrentInterface
    {
        return $this->accessorClient->add($torrentFile);
    }

    /**
     * {@inheritdoc}
     */
    public function getSessionToken(): string
    {
        return $this->miscClient->getSessionToken();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id): TorrentInterface
    {
        return $this->accessorClient->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return $this->accessorClient->getAll();
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return $this->availabilityClient->isAvailable();
    }

    /**
     * {@inheritdoc}
     */
    public function reannounce(TorrentInterface $torrent)
    {
        return $this->miscClient->reannounce($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(TorrentInterface $torrent)
    {
        return $this->removeClient->remove($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAndDeleteLocalData(TorrentInterface $torrent)
    {
        return $this->removeClient->removeAndDeleteLocalData($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function startLater(TorrentInterface $torrent)
    {
        return $this->lifeCycleClient->startLater($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function startNow(TorrentInterface $torrent)
    {
        $this->lifeCycleClient->startNow($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function stop(TorrentInterface $torrent)
    {
        $this->lifeCycleClient->stop($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function updateCache()
    {
        $this->miscClient->updateCache();
    }

    /**
     * {@inheritdoc}
     */
    public function verify(TorrentInterface $torrent)
    {
        $this->miscClient->verify($torrent);
    }
}
