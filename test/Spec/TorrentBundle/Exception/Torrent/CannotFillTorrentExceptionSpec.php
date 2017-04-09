<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Torrent;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Torrent\CannotFillTorrentException;
use TorrentBundle\Exception\Torrent\TorrentException;

class CannotFillTorrentExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CannotFillTorrentException::class);
    }

    public function it_is_a_torrent_exception()
    {
        $this->shouldHaveType(TorrentException::class);
    }
}
