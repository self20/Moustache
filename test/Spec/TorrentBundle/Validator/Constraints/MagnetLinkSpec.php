<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Validator\Constraints;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Validator\Constraints\MagnetLink;

class MagnetLinkSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(MagnetLink::class);
    }
}
