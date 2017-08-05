<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;

class Client implements ClientInterface
{
    /**
     * @var GetClientInterface
     */
    private $getClient;

    /**
     * @var AddClientInterface
     */
    private $addClient;

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
     * @param GetClientInterface       $getClient
     * @param AddClientInterface       $addClient
     * @param LifeCycleClientInterface $lifeCycleClient
     * @param MiscClientInterface      $miscClient
     * @param RemoveClientInterface    $removeClient
     * @param CanBeAvailable           $availabilityClient
     * @param string                   $name
     */
    public function __construct(GetClientInterface $getClient, AddClientInterface $addClient, LifeCycleClientInterface $lifeCycleClient, MiscClientInterface $miscClient, RemoveClientInterface $removeClient, CanBeAvailable $availabilityClient, string $name)
    {
        $this->getClient = $getClient;
        $this->addClient = $addClient;
        $this->lifeCycleClient = $lifeCycleClient;
        $this->miscClient = $miscClient;
        $this->removeClient = $removeClient;
        $this->availabilityClient = $availabilityClient;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function add(TorrentInterface $torrent): TorrentInterface
    {
        return $this->addClient->add($torrent);
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
        return $this->getClient->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return $this->getClient->getAll();
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
    public function start(TorrentInterface $torrent)
    {
        return $this->lifeCycleClient->start($torrent);
    }

    /**
     * {@inheritdoc}
     */
    public function startWithoutLimits(TorrentInterface $torrent)
    {
        $this->lifeCycleClient->startWithoutLimits($torrent);
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
