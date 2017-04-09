<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Torrent;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Torrent\NoUploadedFileException;
use TorrentBundle\Exception\Torrent\TorrentException;

class NoUploadedFileExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(NoUploadedFileException::class);
    }

    public function it_is_a_torrent_exception()
    {
        $this->shouldHaveType(TorrentException::class);
    }
}
