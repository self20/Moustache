<?php

declare(strict_types=1);

namespace Spec\Test\TorrentBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class DoctrineFlusherSpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $entityManager, FilterResponseEvent $event)
    {
        $this->beConstructedWith($entityManager);
    }

    public function it_flushes_doctrine_entity_manager($entityManager, $event)
    {
        $entityManager->flush()->shouldBeCalledTimes(1);

        $this->onKernelResponse($event)->shouldReturn($event);
    }
}
