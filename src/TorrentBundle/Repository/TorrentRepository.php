<?php

declare(strict_types=1);

namespace TorrentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TorrentBundle\Entity\Torrent;

class TorrentRepository extends EntityRepository
{
    const MAX_RESULT = 50;

    /**
     * @param int $userId
     *
     * @return Torrent[]
     */
    public function findAllTorrentsByUser(int $userId): array
    {
        return $this
            ->createQueryBuilder('t')
            ->select('t')
            ->andWhere('t.user = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(self::MAX_RESULT)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int $userId
     * @param int $torrentId
     *
     * @return Torrent|null
     */
    public function findOneByUserAndId(int $userId, int $torrentId)
    {
        return $this
            ->createQueryBuilder('t')
            ->select('t')
            ->andWhere('t.id = :torrentId')
            ->andWhere('t.user = :userId')
            ->setParameter('torrentId', $torrentId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
