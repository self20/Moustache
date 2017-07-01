<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Event;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\ClientAfterEvent;

class ClientAfterEventSpec extends ObjectBehavior
{
    public function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ClientAfterEvent::class);
    }

    public function it_sets_and_return_client($client, ClientInterface $newClient)
    {
        $this->getClient()->shouldReturn($client);

        $this->setClient($newClient);

        $this->getClient()->shouldReturn($newClient);
    }
}
