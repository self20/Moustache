<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;

/**
 * Dispatches a torrent started event when needed.
 */
final class TorrentStartedDispatcherListener
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param TorrentAfterEvent $event
     *
     * @return TorrentAfterEvent
     */
    public function afterTorrentAdded(TorrentAfterEvent $event)
    {
        if (!$event->getTorrent()->isStarted()) {
            return;
        }

        $this->eventDispatcher->dispatch(Events::AFTER_TORRENT_STARTED, $event);

        return $event;
    }
}
