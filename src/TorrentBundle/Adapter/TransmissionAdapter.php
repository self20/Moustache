<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter;

use Transmission\Transmission;

class TransmissionAdapter implements AdapterInterface
{
    // @HEYLISTEN Handle free space, so user will not be blocked because he tries to download more.

    /**
     * @var Transmission
     */
    private $transmissionClient;

    /**
     * @param Transmission $transmissionClient
     */
    public function __construct(Transmission $transmissionClient)
    {
        $this->transmissionClient = $transmissionClient;
    }

    /**
     * {@inheritdoc}
     */
    public function add(string $torrentFilePath, string $savePath = null)
    {
        $torrent = $this->transmissionClient->add($torrentFilePath, false, $savePath);

        return $this->get($torrent->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        try {
            $this->transmissionClient->getClient()->call('', []);

            return true;
        } catch (\RuntimeException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->transmissionClient->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheValues(): array
    {
        $torrents = $this->getAll();

        $hashesAndIds = [];
        foreach ($torrents as $torrent) {
            $hashesAndIds[$torrent->getHash()] = $torrent->getId();
        }

        return $hashesAndIds;
    }

    /**
     * {@inheritdoc}
     */
    public function getSessionToken(): string
    {
        return $this->transmissionClient->getClient()->getToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return $this->transmissionClient->all();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($torrent, $withLocalData)
    {
        return $this->transmissionClient->remove($torrent, $withLocalData);
    }

    /**
     * {@inheritdoc}
     */
    public function startNow($torrent)
    {
        return $this->transmissionClient->start($torrent, $now = true);
    }

    /**
     * {@inheritdoc}
     */
    public function stop($torrent)
    {
        return $this->transmissionClient->stop($torrent);
    }
}
