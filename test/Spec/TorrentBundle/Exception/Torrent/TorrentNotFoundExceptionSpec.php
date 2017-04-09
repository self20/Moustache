<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Torrent;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Torrent\TorrentException;
use TorrentBundle\Exception\Torrent\TorrentNotFoundException;

class TorrentNotFoundExceptionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(4321);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentNotFoundException::class);
    }

    public function it_is_a_torrent_exception()
    {
        $this->shouldHaveType(TorrentException::class);
    }

    public function it_returns_id_not_found()
    {
        $this->getId()->shouldReturn(4321);
    }
}
