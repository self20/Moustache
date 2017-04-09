<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Client;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Client\ClientException;
use TorrentBundle\Exception\Client\TorrentAdapterException;

class TorrentAdapterExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentAdapterException::class);
    }

    public function it_is_a_client_exception()
    {
        $this->shouldHaveType(ClientException::class);
    }
}
