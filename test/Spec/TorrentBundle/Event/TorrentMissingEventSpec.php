<?php

namespace Spec\TorrentBundle\Event;

use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\Event;
use TorrentBundle\Event\TorrentMissingEvent;

class TorrentMissingEventSpec extends ObjectBehavior
{
    public function let(
    ) {
        $this->beConstructedWith('hash');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentMissingEvent::class);
    }

    public function it_is_an_event()
    {
        $this->shouldImplement(Event::class);
    }

    public function it_sets_and_return_hash()
    {
        $this->getHash()->shouldReturn('hash');

        $this->setHash('newHash');

        $this->getHash()->shouldReturn('newHash');
    }
}
