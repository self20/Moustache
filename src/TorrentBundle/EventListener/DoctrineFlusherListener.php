<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Flushes all remaining doctrine persisted entities.
 */
final class DoctrineFlusherListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /*
     * @param FilterResponseEvent $event
     *
     * @return FilterResponseEvent
     */
    public function onKernelResponse(FilterResponseEvent $event) : FilterResponseEvent
    {
        $this->entityManager->flush();

        return $event;
    }
}
