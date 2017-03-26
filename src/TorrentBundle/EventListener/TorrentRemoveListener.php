<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Manager\TorrentManager;

/**
 * Removes a torrent from database when it is removed from client.
 */
final class TorrentRemoveListener
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
    public function afterTorrentRemoved(TorrentAfterEvent $event)
    {
        $this->torrentManager->remove($event->getTorrent())->save();
    }
}
