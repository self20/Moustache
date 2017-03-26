<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle;

use MoustacheBundle\MoustacheBundle;
use PhpSpec\ObjectBehavior;

class MoustacheBundleSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(MoustacheBundle::class);
    }
}
