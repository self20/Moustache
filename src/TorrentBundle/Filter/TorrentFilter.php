<?php

declare(strict_types=1);

namespace TorrentBundle\Filter;

use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Repository\TorrentRepository;

class TorrentFilter implements TorrentFilterInterface
{
    /**
     * @var AuthenticatedUserHelper
     */
    private $authenticatedUserHelper;

    /**
     * @var TorrentRepository
     */
    private $torrentRepository;

    public function __construct(AuthenticatedUserHelper $authenticatedUserHelper, TorrentRepository $torrentRepository)
    {
        $this->authenticatedUserHelper = $authenticatedUserHelper;
        $this->torrentRepository = $torrentRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllAuthenticatedUserTorrents()
    {
        $authenticatedUser = $this->authenticatedUserHelper->get();

        return $this->torrentRepository->findAllTorrentsByUser($authenticatedUser->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthenticatedUserTorrent(int $torrentId)
    {
        $authenticatedUser = $this->authenticatedUserHelper->get();

        return $this->torrentRepository->findOneByUserAndId($authenticatedUser->getId(), $torrentId);
    }
}
