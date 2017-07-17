<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TorrentBundle\Adapter\AdapterInterface;
use TorrentBundle\Client\ExternalTorrentGetter;
use TorrentBundle\Client\RemoveClient;
use TorrentBundle\Client\RemoveClientInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\Events;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\Exception\Torrent\CannotRemoveTorrentException;

class RemoveClientSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        AdapterInterface $externalClient,
        ExternalTorrentGetter $externalTorrentGetter,

        TorrentInterface $externalTorrent,
        TorrentInterface $torrent
    ) {
        $torrent->getHash()->willReturn('hash');
        $torrent->getFriendlyName()->willReturn('friendly name');

        $eventDispatcher->dispatch(Argument::type('string'), Argument::type(Event::class))->willReturn(null);

        $externalClient->remove($externalTorrent, Argument::type('bool'))->willReturn();

        $externalTorrentGetter->get('hash')->willReturn($externalTorrent);

        $this->beConstructedWith($eventDispatcher, $externalClient, $externalTorrentGetter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RemoveClient::class);
    }

    public function it_is_an_remove_client()
    {
        $this->shouldImplement(RemoveClientInterface::class);
    }

    public function it_throws_an_exception_if_torrent_failed_to_be_removed($externalClient, $eventDispatcher, $externalTorrent, $torrent)
    {
        $externalClient->remove($externalTorrent, Argument::type('bool'))->willThrow(new \Exception());

        $eventDispatcher->dispatch(Argument::type('string'), Argument::type(Event::class))->shouldNotBeCalled();

        $this->shouldThrow(CannotRemoveTorrentException::class)->during('remove', [$torrent]);
        $this->shouldThrow(CannotRemoveTorrentException::class)->during('removeAndDeleteLocalData', [$torrent]);
    }

    public function it_removes_given_torrent($externalClient, $externalTorrent, $torrent)
    {
        $externalClient->remove($externalTorrent, false)->shouldBeCalledTimes(1);

        $this->remove($torrent);

        $externalClient->remove($externalTorrent, true)->shouldBeCalledTimes(1);

        $this->removeAndDeleteLocalData($torrent);
    }

    public function it_dispatches_an_after_torrent_remove_event($eventDispatcher, $torrent)
    {
        $eventDispatcher->dispatch(Events::AFTER_TORRENT_REMOVED, Argument::type(TorrentAfterEvent::class))->shouldBeCalledTimes(2);

        $this->remove($torrent);
        $this->removeAndDeleteLocalData($torrent);
    }
}
