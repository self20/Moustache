<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\EventListener;

use MoustacheBundle\EventListener\MissingTorrentWarnerListener;
use MoustacheBundle\Service\FlashMessageGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\TorrentMissingEvent;

class MissingTorrentWarnerListenerSpec extends ObjectBehavior
{
    public function let(
        LoggerInterface $logger,
        FlashMessageGenerator $flashMessageGenerator,
        ClientInterface $client,

        TorrentMissingEvent $event
    ) {
        $event->getHash()->willReturn('hash');

        $client->getName()->willReturn('client name');

        $this->beConstructedWith($logger, $flashMessageGenerator, $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MissingTorrentWarnerListener::class);
    }

    public function it_warns_user_that_a_torrent_is_missing($flashMessageGenerator, $event)
    {
        $flashMessageGenerator->warnTorrentIsMissing()->shouldBeCalledTimes(1);

        $this->onTorrentMissing($event)->shouldReturn($event);
    }

    public function it_warns_admin_that_a_torrent_is_missing($logger, $event)
    {
        $logger->error(Argument::type('string'), Argument::cetera())->shouldBeCalledTimes(1);

        $this->onTorrentMissing($event)->shouldReturn($event);
    }
}
