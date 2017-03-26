<?php

declare(strict_types=1);

namespace TorrentBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use TorrentBundle\Entity\TorrentInterface;

class TorrentAfterEvent extends Event
{
    /**
     * @var TorrentInterface
     */
    private $torrent;

    public function __construct(TorrentInterface $torrent)
    {
        $this->torrent = $torrent;
    }

    /**
     * @return TorrentInterface
     */
    public function getTorrent(): TorrentInterface
    {
        return $this->torrent;
    }

    /**
     * @param TorrentInterface $torrent
     */
    public function setTorrent(TorrentInterface $torrent)
    {
        $this->torrent = $torrent;
    }
}
