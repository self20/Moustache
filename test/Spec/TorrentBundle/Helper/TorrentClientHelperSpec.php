<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\ClientAfterEvent;
use TorrentBundle\Event\Events;
use TorrentBundle\Exception\Configuration\BadTorrentClientNameException;
use TorrentBundle\Exception\Configuration\NoClientConnectorOpen;
use TorrentBundle\Exception\Configuration\NoClientServiceAvailableException;
use TorrentBundle\Helper\HelperGetterInterface;
use TorrentBundle\Helper\TorrentClientHelper;

class TorrentClientHelperSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $eventDispatcher)
    {
        $this->beConstructedWith($eventDispatcher, 'host', 5555, 'name');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentClientHelper::class);
    }

    public function it_is_a_helper_getter()
    {
        $this->shouldHaveType(HelperGetterInterface::class);
    }

    public function it_tells_if_client_collection_is_empty(ClientInterface $client)
    {
        $this->isEmpty()->shouldReturn(true);

        $this->addTorrentClient($client, 'name');

        $this->isEmpty()->shouldReturn(false);
    }

    public function it_returns_client_without_any_checks(ClientInterface $client)
    {
        $this->getWhenAvailable()->shouldReturn(null);

        $this->addTorrentClient($client, 'name');

        $this->getWhenAvailable()->shouldReturn($client);
    }

    public function it_throws_an_exception_if_no_client_at_all_is_available()
    {
        $this->shouldThrow(NoClientServiceAvailableException::class)->during('get');
    }

    public function it_throws_an_exception_if_no_client_is_found_with_configured_name(ClientInterface $client)
    {
        $this->addTorrentClient($client, 'bad_name');

        $this->shouldThrow(BadTorrentClientNameException::class)->during('get');
    }

    public function it_throws_an_exception_if_client_is_not_available(ClientInterface $client)
    {
        $client->isAvailable()->willReturn(false);
        $this->addTorrentClient($client, 'name');

        $this->shouldThrow(NoClientConnectorOpen::class)->during('get');
    }

    public function it_returns_client(ClientInterface $client)
    {
        $client->isAvailable()->willReturn(true);
        $this->addTorrentClient($client, 'name');

        $this->get()->shouldReturn($client);
    }

    public function it_dispatches_an_event_when_client_is_retrieved($eventDispatcher, ClientInterface $client)
    {
        $client->isAvailable()->willReturn(true);
        $this->addTorrentClient($client, 'name');

        $eventDispatcher->dispatch(Events::AFTER_CLIENT_RETRIEVED, Argument::type(ClientAfterEvent::class))->shouldBeCalledTimes(2);

        $this->get();
        $this->getWhenAvailable();
    }
}
