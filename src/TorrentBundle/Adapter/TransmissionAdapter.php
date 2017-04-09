<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter;

use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Exception\Torrent\NoUploadedFileException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;
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
    public function add(TorrentInterface $torrent, string $savePath = null)
    {
        if (null === $torrent->getUploadedFile()) {
            throw new NoUploadedFileException('Tried to add a torrent with transmission but no .torrent file was provided.');
        }
        $torrent = $this->transmissionClient->add($torrent->getUploadedFile()->getRealPath(), false, $savePath);

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
        $externalTorrent = $this->doGetTorrent($id);

        if (empty($externalTorrent)) {
            throw new TorrentNotFoundException($id, 0);
        }

        return $externalTorrent;
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

    private function doGetTorrent(int $id)
    {
        try {
            return $this->transmissionClient->get($id);
        } catch (\RuntimeException $ex) {
            throw new TorrentNotFoundException($id, 0, $ex);
        }
    }
}
