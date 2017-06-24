<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\EventListener;

use MoustacheBundle\Event\TorrentMissingEvent;
use MoustacheBundle\EventListener\MissingTorrentWarnerListener;
use MoustacheBundle\Helper\FlashBagHelper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use TorrentBundle\Client\ClientInterface;

class MissingTorrentWarnerListenerSpec extends ObjectBehavior
{
    public function let(
        LoggerInterface $logger,
        FlashBagHelper $flashBagHelper,
        ClientInterface $client,
        RequestStack $requestStack,

        TorrentMissingEvent $event,
        Request $request
    ) {
        $request->isXmlHttpRequest()->willReturn(false);
        $requestStack->getCurrentRequest()->willReturn($request);

        $event->getHash()->willReturn('hash');

        $client->getName()->willReturn('client name');

        $this->beConstructedWith($logger, $flashBagHelper, $client, $requestStack);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MissingTorrentWarnerListener::class);
    }

    public function it_ignores_ajax_requests($request, $flashBagHelper, $logger, $event)
    {
        $request->isXmlHttpRequest()->willReturn(true);

        $flashBagHelper->warnTorrentIsMissing()->shouldNotBeCalled();
        $logger->error(Argument::any())->shouldNotBeCalled();

        $this->onTorrentMissing($event)->shouldReturn(null);
    }

    public function it_warns_user_that_a_torrent_is_missing($flashBagHelper, $event)
    {
        $flashBagHelper->warnTorrentIsMissing()->shouldBeCalledTimes(1);

        $this->onTorrentMissing($event)->shouldReturn($event);
    }

    public function it_warns_admin_that_a_torrent_is_missing($logger, $event)
    {
        $logger->error(Argument::type('string'), Argument::cetera())->shouldBeCalledTimes(1);

        $this->onTorrentMissing($event)->shouldReturn($event);
    }
}
