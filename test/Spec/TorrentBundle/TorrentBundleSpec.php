<?php

declare(strict_types=1);

namespace Spec\TorrentBundle;

use PhpSpec\ObjectBehavior;
use TorrentBundle\TorrentBundle;

class TorrentBundleSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentBundle::class);
    }
}
