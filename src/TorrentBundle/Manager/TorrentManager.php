<?php

declare(strict_types=1);

namespace TorrentBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Helper\AuthenticatedUserHelper;

class TorrentManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var AuthenticatedUserHelper
     */
    private $authenticatedUserHelper;

    /**
     * @param EntityManagerInterface  $entityManager
     * @param AuthenticatedUserHelper $authenticatedUserHelper
     */
    public function __construct(EntityManagerInterface $entityManager, AuthenticatedUserHelper $authenticatedUserHelper)
    {
        $this->entityManager = $entityManager;
        $this->authenticatedUserHelper = $authenticatedUserHelper;
    }

    /**
     * @param TorrentInterface $torrent
     *
     * @return self
     */
    public function remove(TorrentInterface $torrent): self
    {
        $this->entityManager->remove($torrent);

        return $this;
    }

    /**
     * @param TorrentInterface $torrent
     *
     * @return self
     */
    public function persist(TorrentInterface $torrent): self
    {
        if (null === $torrent->getUser()) {
            $torrent->setUser($this->authenticatedUserHelper->get());
        }

        $this->entityManager->persist($torrent);

        return $this;
    }

    public function save()
    {
        $this->entityManager->flush();
    }
}
