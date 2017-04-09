<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;
use TorrentBundle\Repository\TorrentRepository;

class TorrentRepositorySpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $entityManagern, ClassMetadata $classMetadata)
    {
        $this->beConstructedWith($entityManagern, $classMetadata);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentRepository::class);
    }

    public function it_is_an_entity_repository()
    {
        $this->shouldHaveType(EntityRepository::class);
    }
}
