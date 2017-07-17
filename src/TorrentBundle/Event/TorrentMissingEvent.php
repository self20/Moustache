<?php

declare(strict_types=1);

namespace TorrentBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class TorrentMissingEvent extends Event
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @param string $hash
     */
    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash)
    {
        $this->hash = $hash;
    }
}
