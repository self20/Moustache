<?php

declare(strict_types=1);

namespace Spec\Test\TorrentBundle\Entity;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Entity\EntityInterface;

class UserSpec extends ObjectBehavior
{
    public function it_is_an_entity()
    {
        $this->shouldImplement(EntityInterface::class);
    }

    public function it_tells_if_it_is_new()
    {
        $this->isNew()->shouldReturn(true);
    }

    public function it_tells_if_it_is_not_new()
    {
        $this->setCurrentMessage(3);

        $this->isNew()->shouldReturn(false);
    }
}
