<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Manager\TorrentManager;

/**
 * Adds a torrent to the database when it is added from client.
 */
final class TorrentAddListener
{
    /**
     * @var TorrentManager
     */
    private $torrentManager;

    /**
     * @param TorrentManager $torrentManager
     */
    public function __construct(TorrentManager $torrentManager)
    {
        $this->torrentManager = $torrentManager;
    }

    /**
     * @param TorrentAfterEvent $event
     *
     * @return TorrentAfterEvent
     */
    public function afterTorrentAdded(TorrentAfterEvent $event)
    {
        $this->torrentManager->persist($event->getTorrent())->save();

        return $event;
    }
}
