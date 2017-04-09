<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Client;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Client\CacheOutdatedException;
use TorrentBundle\Exception\Client\ClientException;

class CacheOutdatedExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CacheOutdatedException::class);
    }

    public function it_is_a_client_exception()
    {
        $this->shouldHaveType(ClientException::class);
    }
}
