<?php

declare(strict_types=1);

namespace TorrentBundle\Helper;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\ClientAfterEvent;
use TorrentBundle\Event\Events;
use TorrentBundle\Exception\Configuration\BadTorrentClientNameException;
use TorrentBundle\Exception\Configuration\NoClientConnectorOpen;
use TorrentBundle\Exception\Configuration\NoClientServiceAvailableException;

class TorrentClientHelper implements HelperGetterInterface
{
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
     * @var string
     */
    private $rpcName;

    /**
     * @var ClientInterface[]
     */
    private $torrentClients = [];

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $rpcHost
     * @param int $rpcPort
     * @param string $rpcName
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, string $rpcHost, int $rpcPort, string $rpcName)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->rpcHost = $rpcHost;
        $this->rpcPort = $rpcPort;
        $this->rpcName = $rpcName;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return !isset($this->torrentClients[$this->rpcName]);
    }

    /**
     * @return ClientInterface|null
     */
    public function getWhenAvailable()
    {
        if ($this->isEmpty()) {
            return;
        }

        return $this->torrentClients[$this->rpcName];
    }

    /**
     * @throws NoClientServiceAvailableException
     * @throws BadTorrentClientNameException
     * @throws NoClientConnectorOpen
     *
     * @return ClientInterface
     */
    public function get()
    {
        $this->checkAtLeastOneClientAvailable();
        $this->checkConfiguredClientName();
        $this->checkClientAvailability();

        $this->eventDispatcher->dispatch(Events::AFTER_CLIENT_RETRIEVED, new ClientAfterEvent($this->torrentClients[$this->rpcName]));

        return $this->torrentClients[$this->rpcName];
    }

    /**
     * @param ClientInterface $torrentClient
     * @param string $clientName
     */
    public function addTorrentclient(ClientInterface $torrentClient, string $clientName)
    {
        $this->torrentClients[$clientName] = $torrentClient;
    }

    private function checkAtLeastOneClientAvailable()
    {
        if (empty($this->torrentClients)) {
            throw new NoClientServiceAvailableException('No torrent client is configured. This should never happened, please submit an issue to moustache developpers.');
        }
    }

    private function checkConfiguredClientName()
    {
        if ($this->isEmpty()) {
            throw new BadTorrentClientNameException(sprintf(
                'Torrent RPC client name “%s” given in parameters is invalid. Available: [%s]', $this->rpcName, implode(', ', array_keys($this->torrentClients))
            ));
        }
    }

    private function checkClientAvailability()
    {
        if (!$this->torrentClients[$this->rpcName]->isAvailable()) {
            throw new NoClientConnectorOpen(
                sprintf('No %s client is listening on %s:%s', $this->rpcName, $this->rpcHost, $this->rpcPort)
            );
        }
    }
}
