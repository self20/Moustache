<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Repository\TorrentRepository;

class TorrentRepositorySpec extends ObjectBehavior
{
    public function let(
        EntityManagerInterface $entityManager,
        ClassMetadata $classMetadata,

        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        TorrentInterface $torrent
    ) {
        $query->getResult(Argument::any())->willReturn(['result']);
        $query->getOneOrNullResult(Argument::any())->willReturn($torrent);

        $queryBuilder->select(Argument::cetera())->willReturn($queryBuilder);
        $queryBuilder->from(Argument::cetera())->willReturn($queryBuilder);
        $queryBuilder->where(Argument::cetera())->willReturn($queryBuilder);
        $queryBuilder->andWhere(Argument::cetera())->willReturn($queryBuilder);
        $queryBuilder->setParameter(Argument::cetera())->willReturn($queryBuilder);
        $queryBuilder->setMaxResults(Argument::cetera())->willReturn($queryBuilder);
        $queryBuilder->getQuery(Argument::cetera())->willReturn($query);

        $entityManager->createQueryBuilder()->willReturn($queryBuilder);

        $this->beConstructedWith($entityManager, $classMetadata);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentRepository::class);
    }

    public function it_is_an_entity_repository()
    {
        $this->shouldHaveType(EntityRepository::class);
    }

    public function it_finds_all_torrents_for_a_given_user()
    {
        $this->findAllTorrentsByUser(1)->shouldReturn(['result']);
    }

    public function it_finds_on_torrent_by_user_and_id($torrent)
    {
        $this->findOneByUserAndId(1, 2)->shouldReturn($torrent);
    }
}
