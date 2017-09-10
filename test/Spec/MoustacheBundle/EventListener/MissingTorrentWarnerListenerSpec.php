<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\EventListener;

use MoustacheBundle\EventListener\MissingTorrentWarnerListener;
use MoustacheBundle\Message\CanDispatchMessage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\TorrentMissingEvent;

class MissingTorrentWarnerListenerSpec extends ObjectBehavior
{
    public function let(
        ClientInterface $client,
        CanDispatchMessage $messageDispatcher,
        RequestStack $requestStack,

        TorrentMissingEvent $event,
        Request $request
    ) {
        $event->getHash()->willReturn('hash');

        $client->getName()->willReturn('client name');

        $request->isXmlHttpRequest()->willReturn(false);
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($client, $messageDispatcher, $requestStack);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MissingTorrentWarnerListener::class);
    }

    public function it_does_nothing_if_request_is_ajax($messageDispatcher, $request, $event)
    {
        $request->isXmlHttpRequest()->willReturn(true);

        $messageDispatcher->error(Argument::cetera())->shouldNotBeCalled();

        $this->onTorrentMissing($event)->shouldReturn(null);
    }

    public function it_dispatches_an_error_message_about_the_missing_torrent($messageDispatcher, $event)
    {
        $messageDispatcher->error(CanDispatchMessage::TORRENT_IS_MISSING, 'hash', 'client name', 'client name')->shouldBeCalledTimes(1);

        $this->onTorrentMissing($event)->shouldReturn($event);
    }
}
