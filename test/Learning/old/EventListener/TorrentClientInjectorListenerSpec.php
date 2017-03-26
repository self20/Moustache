<?php

declare(strict_types=1);

namespace Spec\Test\TorrentBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use TorrentBundle\Adapter\TorrentClientInterface;
use TorrentBundle\EventListener\TorrentClientInjectorListener;
use TorrentBundle\Exception\NoClientAvailable;
use TorrentBundle\Exception\NoClientConnectorOpen;
use TorrentBundle\Helper\HelperInterface;

class TorrentClientInjectorListenerSpec extends ObjectBehavior
{
    public function let(
        HelperInterface $torrentClientHelper,
        TorrentClientInterface $transmissionClient,
        TorrentClientInterface $fakeClient,

        GetResponseEvent $event
    ) {
        $torrentClientHelper->isEmpty()->willReturn(true);
        $transmissionClient->isAvailable()->willReturn(true);

        $this->beConstructedWith($torrentClientHelper, $transmissionClient, $fakeClient, TorrentClientInjectorListener::CLIENT_TRANSMISSION);
    }

    public function it_throws_an_exception_when_no_client_seems_available(
        $torrentClientHelper, $transmissionClient, $fakeClient, $event
    ) {
        $this->beConstructedWith($torrentClientHelper, $transmissionClient, $fakeClient, 'wrong_client');

        $this->shouldThrow(NoClientAvailable::class)->during('onKernelRequest', [$event]);
    }

    public function it_throws_an_exception_when_transmission_client_is_unavailable($transmissionClient, $event)
    {
        $transmissionClient->isAvailable()->willReturn(false);

        $this->shouldThrow(NoClientConnectorOpen::class)->during('onKernelRequest', [$event]);
    }

    public function it_does_nothing_if_a_client_had_already_been_set($torrentClientHelper, $event)
    {
        $torrentClientHelper->isEmpty()->willReturn(false);

        $torrentClientHelper->set(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event)->shouldReturn(null);
    }

    public function it_sets_transmission_client_when_it_is_supported_and_available($torrentClientHelper, $transmissionClient, $event)
    {
        $torrentClientHelper->set($transmissionClient)->shouldBeCalledTimes(1);

        $this->onKernelRequest($event)->shouldReturn($event);
    }

    public function it_sets_fake_client_when_it_is_supported(
        $torrentClientHelper, $transmissionClient, $fakeClient, $event
    ) {
        $this->beConstructedWith($torrentClientHelper, $transmissionClient, $fakeClient, TorrentClientInjectorListener::CLIENT_FAKE);

        $torrentClientHelper->set($fakeClient)->shouldBeCalledTimes(1);

        $this->onKernelRequest($event)->shouldReturn($event);
    }
}
