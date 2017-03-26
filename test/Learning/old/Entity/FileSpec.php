<?php

declare(strict_types=1);

namespace Spec\Test\TorrentBundle\Entity;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Entity\EntityInterface;

class FileSpec extends ObjectBehavior
{
    public function it_is_an_entity()
    {
        $this->shouldImplement(EntityInterface::class);
    }
}
