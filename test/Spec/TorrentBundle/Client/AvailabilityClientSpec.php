<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Client\AvailabilityClient;
use TorrentBundle\Client\CanBeAvailable;

class AvailabilityClientSpec extends ObjectBehavior
{
    public function let(AdapterInterface $externalClient)
    {
        $externalClient->isAvailable()->willReturn(true);

        $this->beConstructedWith($externalClient);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AvailabilityClient::class);
    }

    public function it_is_an_availability_client()
    {
        $this->shouldImplement(CanBeAvailable::class);
    }

    public function it_says_whether_or_not_it_is_available($externalClient)
    {
        $externalClient->isAvailable()->shouldBeCalledTimes(1);

        $this->isAvailable()->shouldReturn(true);
    }
}
