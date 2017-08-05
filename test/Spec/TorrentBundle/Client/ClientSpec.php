<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Client;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Client\AddClientInterface;
use TorrentBundle\Client\CanBeAvailable;
use TorrentBundle\Client\Client;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Client\GetClientInterface;
use TorrentBundle\Client\LifeCycleClientInterface;
use TorrentBundle\Client\MiscClientInterface;
use TorrentBundle\Client\RemoveClientInterface;
use TorrentBundle\Entity\TorrentInterface;

class ClientSpec extends ObjectBehavior
{
    public function let(
        GetClientInterface $getClient,
        AddClientInterface $addClient,
        LifeCycleClientInterface $lifeCycleClient,
        MiscClientInterface $miscClient,
        RemoveClientInterface $removeClient,
        CanBeAvailable $availabilityClient,

        TorrentInterface $torrent
    ) {
        $getClient->get(1)->willReturn($torrent);
        $getClient->getAll()->willReturn([$torrent]);

        $miscClient->getSessionToken()->willReturn('session token');

        $availabilityClient->isAvailable()->willReturn(true);

        $this->beConstructedWith($getClient, $addClient, $lifeCycleClient, $miscClient, $removeClient, $availabilityClient, 'client');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    public function it_is_an_misc_client()
    {
        $this->shouldImplement(ClientInterface::class);
    }

    public function it_adds_a_torrent($torrent, $addClient)
    {
        $addClient->add($torrent)->shouldBeCalledTimes(1);

        $this->add($torrent);
    }

    public function it_returns_session_token()
    {
        $this->getSessionToken()->shouldReturn('session token');
    }

    public function it_returns_its_name()
    {
        $this->getName()->shouldReturn('client');
    }

    public function it_gets_a_single_torrent($torrent)
    {
        $this->get(1)->shouldReturn($torrent);
    }

    public function it_gets_all_the_torrents($torrent)
    {
        $this->getAll()->shouldReturn([$torrent]);
    }

    public function it_tells_its_availability()
    {
        $this->isAvailable()->shouldReturn(true);
    }

    public function it_reannounce_a_torrent($torrent, $miscClient)
    {
        $miscClient->reannounce($torrent)->shouldBeCalledTimes(1);

        $this->reannounce($torrent);
    }

    public function it_removes_a_torrent($torrent, $removeClient)
    {
        $removeClient->remove($torrent)->shouldBeCalledTimes(1);

        $this->remove($torrent);
    }

    public function it_removes_a_torrent_and_deletes_local_data($torrent, $removeClient)
    {
        $removeClient->removeAndDeleteLocalData($torrent)->shouldBeCalledTimes(1);

        $this->removeAndDeleteLocalData($torrent);
    }

    public function it_starts_a_torrent($torrent, $lifeCycleClient)
    {
        $lifeCycleClient->start($torrent)->shouldBeCalledTimes(1);

        $this->start($torrent);
    }

    public function it_starts_a_torrent_ignoring_limits($torrent, $lifeCycleClient)
    {
        $lifeCycleClient->startWithoutLimits($torrent)->shouldBeCalledTimes(1);

        $this->startWithoutLimits($torrent);
    }

    public function it_stops_a_torrent($torrent, $lifeCycleClient)
    {
        $lifeCycleClient->stop($torrent)->shouldBeCalledTimes(1);

        $this->stop($torrent);
    }

    public function it_updates_the_torrent_cache($miscClient)
    {
        $miscClient->updateCache()->shouldBeCalledTimes(1);

        $this->updateCache();
    }

    public function it_verifies_a_torrent($torrent, $miscClient)
    {
        $miscClient->verify($torrent)->shouldBeCalledTimes(1);

        $this->verify($torrent);
    }
}
