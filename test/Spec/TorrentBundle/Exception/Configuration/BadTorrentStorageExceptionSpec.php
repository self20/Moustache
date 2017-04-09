<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Exception\Configuration;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Exception\Configuration\BadTorrentStorageException;
use TorrentBundle\Exception\Configuration\ConfigurationException;

class BadTorrentStorageExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(BadTorrentStorageException::class);
    }

    public function it_is_a_configuration_exception()
    {
        $this->shouldHaveType(ConfigurationException::class);
    }
}
