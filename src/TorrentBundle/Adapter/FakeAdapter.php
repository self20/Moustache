<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter;

use StandardBundle\CanDownload;
use Symfony\Component\HttpFoundation\Session\Session;
use TorrentBundle\DataFixtures\Data\TorrentData;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Exception\Torrent\NoUploadedFileException;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Helper\TorrentStorageHelper;

class FakeAdapter implements AdapterInterface
{
    const SESSION_KEY = 'fake_torrents';

    /**
     * @var AuthenticatedUserHelper
     */
    private $authenticatedUserHelper;

    /**
     * @var TorrentStorageHelper
     */
    private $torrentStorageHelper;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param AuthenticatedUserHelper $authenticatedUserHelper
     * @param TorrentStorageHelper    $torrentStorageHelper
     * @param Session                 $session
     */
    public function __construct(AuthenticatedUserHelper $authenticatedUserHelper, TorrentStorageHelper $torrentStorageHelper, Session $session)
    {
        $this->authenticatedUserHelper = $authenticatedUserHelper;
        $this->torrentStorageHelper = $torrentStorageHelper;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function add(TorrentInterface $torrent, string $savePath = null)
    {
        if (null === $torrent->getUploadedFile()) {
            throw new NoUploadedFileException('Tried to add a torrent with fake but no .torrent file was provided.');
        }

        $this->retreiveTorrentsFromSession();

        $count = count(TorrentData::$torrents) + 1;

        TorrentData::createOneTorrent([
            'id' => $count, 'hash' => sha1((string) $count),
            'user' => $this->authenticatedUserHelper->get(), 'status' => CanDownload::STATUS_DONE, 'downloadDir' => $this->torrentStorageHelper->get(),
            'name' => 'torrent-'.$count, 'friendlyName' => 'torrent-'.$count, 'mime' => 'directory',
            'startDate' => new \DateTime(),
            'nbPeers' => 0, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => $this->getTorrentTotalByteSize($torrent), 'currentByteSize' => 0,
        ], $torrent);

        $this->session->set(self::SESSION_KEY, TorrentData::$torrents);

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
        $this->retreiveTorrentsFromSession();

        return TorrentData::$torrents[$id] ?? null;
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
        $this->retreiveTorrentsFromSession();

        return TorrentData::$torrents;
    }

    /**
     * {@inheritdoc}
     */
    public function reannounce($torrent)
    {
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
    public function start($torrent)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function startWithoutLimits($torrent)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function verify($torrent)
    {
    }

    private function retreiveTorrentsFromSession()
    {
        if ($this->session->has(self::SESSION_KEY)) {
            TorrentData::$torrents = $this->session->get(self::SESSION_KEY);
        } else {
            TorrentData::createAll();
            $this->session->set(self::SESSION_KEY, TorrentData::$torrents);
        }
    }

    private function getTorrentTotalByteSize(TorrentInterface $torrent)
    {
        if ('big.torrent' === $torrent->getUploadedFile()->getClientOriginalName()) {
            return 80000000000000;
        }

        return 12345678;
    }
}
