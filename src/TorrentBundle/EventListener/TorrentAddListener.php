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

    public function __construct(TorrentManager $torrentManager)
    {
        $this->torrentManager = $torrentManager;
    }

    /**
     * @param TorrentAfterEvent $event
     */
    public function afterTorrentAdded(TorrentAfterEvent $event)
    {
        $this->torrentManager->persist($event->getTorrent())->save();
    }
}
