<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter;

use TorrentBundle\DataFixtures\Data\TorrentData;
use TorrentBundle\Entity\CanDownload;
use TorrentBundle\Entity\Torrent;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Helper\TorrentStorageHelper;

class FakeAdapter implements AdapterInterface
{
    /**
     * @var AuthenticatedUserHelper
     */
    private $authenticatedUserHelper;

    /**
     * @var TorrentStorageHelper
     */
    private $torrentStorageHelper;

    /**
     * @param AuthenticatedUserHelper $authenticatedUserHelper
     * @param TorrentStorageHelper    $torrentStorageHelper
     */
    public function __construct(AuthenticatedUserHelper $authenticatedUserHelper, TorrentStorageHelper $torrentStorageHelper)
    {
        $this->authenticatedUserHelper = $authenticatedUserHelper;
        $this->torrentStorageHelper = $torrentStorageHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function add(string $torrentFilePath, string $savePath = null)
    {
        TorrentData::createAll();

        $count = count(TorrentData::$torrents) + 1;

        $torrent = new Torrent();
        $torrent->setId($count);
        $torrent->setHash(sha1((string) $count));
        $torrent->setUser($this->authenticatedUserHelper->get());
        $torrent->setName('torrent-'.$count);
        $torrent->setStartDate(new \DateTime());
        $torrent->setDownloadDir($this->torrentStorageHelper->get());
        $torrent->setDownloadRate(0);
        $torrent->setUploadRate(0);
        $torrent->setNbPeers(0);
        $torrent->setTotalByteSize(12345678);
        $torrent->setDownloadedByteSize(12345678);
        $torrent->setFriendlyName('torrent-'.$count);
        $torrent->setStatus(CanDownload::STATUS_DONE);
        $torrent->setMime('directory');
        TorrentData::$torrents[$count] = $torrent;

        return $torrent;
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        TorrentData::createAll();

        return TorrentData::$torrents[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheValues(): array
    {
        $torrents = $this->getAll();

        $hashesAndIds = [];
        $i = 1;
        foreach ($torrents as $torrent) {
            $hashesAndIds[$torrent->getHash()] = $i++;
        }

        return $hashesAndIds;
    }

    /**
     * {@inheritdoc}
     */
    public function getSessionToken(): string
    {
        return 'fake_token';
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        TorrentData::createAll();

        return TorrentData::$torrents;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($torrent, $withLocalData)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function stop($torrent)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function startNow($torrent)
    {
    }
}
