<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use TorrentBundle\EventListener\DoctrineFlusherListener;

class DoctrineFlusherListenerSpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $entityManager, FilterResponseEvent $event)
    {
        $entityManager->flush()->willReturn(null);

        $this->beConstructedWith($entityManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineFlusherListener::class);
    }

    public function it_flushes_the_entity_manager($entityManager, $event)
    {
        $entityManager->flush()->shouldBeCalledTimes(1);

        $this->onKernelResponse($event)->shouldReturn($event);
    }
}
