<?php

declare(strict_types=1);

namespace TorrentBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\ClientAfterEvent;
use TorrentBundle\Event\Events;
use TorrentBundle\Exception\NoClientConnectorOpen;
use TorrentBundle\Exception\NoClientServiceAvailable;

class TorrentClientHelper implements HelperGetterInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var HelperGetterInterface
     */
    private $torrentClientNameHelper;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var string
     */
    private $rpcHost;

    /**
     * @var int
     */
    private $rpcPort;

    /**
     * @param ContainerInterface       $container
     * @param HelperGetterInterface    $torrentClientNameHelper
     * @param EventDispatcherInterface $eventDispatcher
     * @param string                   $rpcHost
     * @param int                      $rpcPort
     */
    public function __construct(ContainerInterface $container, HelperGetterInterface $torrentClientNameHelper, EventDispatcherInterface $eventDispatcher, string $rpcHost, int $rpcPort)
    {
        $this->container = $container;
        $this->torrentClientNameHelper = $torrentClientNameHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->rpcHost = $rpcHost;
        $this->rpcPort = $rpcPort;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return null === $this->getWhenAvailable();
    }

    /**
     * @throws NoClientServiceAvailable
     *
     * @return ClientInterface
     */
    public function getWhenAvailable()
    {
        $clientName = $this->torrentClientNameHelper->get();
        $clientServiceName = 'torrent.client.'.$clientName;

        if (!$this->container->has($clientServiceName)) {
            throw new NoClientServiceAvailable(sprintf(
                'A valid torrent RPC client was configured (%s) but the corresponding service configuration is missing.', $clientName
            ));
        }

        return $this->container->get($clientServiceName);
    }

    /**
     * @throws NoClientServiceAvailable
     * @throws NoClientConnectorOpen
     *
     * @return ClientInterface
     */
    public function get()
    {
        $client = $this->getWhenAvailable();

        if (!$client->isAvailable()) {
            throw new NoClientConnectorOpen(
                sprintf('No %s client is listening on %s:%s', $client->getName(), $this->rpcHost, $this->rpcPort)
            );
        }

        $this->eventDispatcher->dispatch(Events::AFTER_CLIENT_INITIALIZED, new ClientAfterEvent($client));

        return $client;
    }
}
