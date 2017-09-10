<?php

declare(strict_types=1);

namespace Spec\Learning;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Transmission\Client;
use Transmission\Model\File;
use Transmission\Model\Session;
use Transmission\Model\Stats\Session as SessionStats;
use Transmission\Model\Torrent as ExternalTorrent;
use Transmission\Transmission as RealTransmission;

class TransmissionSpec extends ObjectBehavior
{
    public function let(
        RealTransmission $transmission,
        ExternalTorrent $torrent,
        Session $session,
        SessionStats $sessionStats,
        File $file,
        Client $client
    ) {
    }

    public function it_can_get($transmission, $torrent)
    {
        $transmission->get(Argument::type('int'))->willReturn($torrent);
    }

    public function it_can_get_all($transmission, $torrent)
    {
        $transmission->all()->willReturn([$torrent]);
    }

    public function it_can_add($transmission, $torrent)
    {
        $transmission->add(Argument::type('string'))->willReturn($torrent);
    }

    public function it_can_start($transmission, $torrent)
    {
        $transmission->start($torrent)->willReturn(true);
    }

    public function it_can_stop($transmission, $torrent)
    {
        $transmission->stop($torrent)->willReturn(true);
    }

    public function it_can_verify($transmission, $torrent)
    {
        $transmission->verify($torrent)->willReturn(true);
    }

    public function it_can_remove($transmission, $torrent)
    {
        $transmission->remove($torrent)->willReturn(true);
    }

    public function it_can_reannounce($transmission, $torrent)
    {
        $transmission->reannounce($torrent)->willReturn(true);
    }

    public function it_returns_session($transmission, $session)
    {
        $transmission->getSession()->willReturn($session);
    }

    public function it_returns_session_stats($transmission, $sessionStats)
    {
        $transmission->getSessionStats()->willReturn($sessionStats);
    }

    public function it_returns_client($transmission, $client)
    {
        $transmission->getClient()->willReturn($client);
    }

    public function it_returns_free_space($transmission)
    {
        $transmission->getFreeSpace()->willReturn(32432);
    }

    public function it_returns_torrent_with_hash($torrent)
    {
        $torrent->getHash()->willReturn('ABC');
    }

    public function it_returns_torrent_with_upload_rate($torrent)
    {
        $torrent->getUploadRate()->willReturn(1);
    }

    public function it_returns_torrent_with_download_rate($torrent)
    {
        $torrent->getDownloadRate()->willReturn(1);
    }

    public function it_returns_torrent_with_status($torrent)
    {
        $torrent->getDownloadRate()->willReturn('ABC');
    }

    public function it_returns_torrent_with_start_date($torrent)
    {
        $torrent->getStartDate()->willReturn(1);
    }

    public function it_returns_torrent_with_size($torrent)
    {
        $torrent->getSize()->willReturn(1);
    }

    public function it_returns_torrent_with_percent_done($torrent)
    {
        $torrent->getPercentDone()->willReturn(1);
    }

    public function it_returns_torrent_with_name($torrent)
    {
        $torrent->getName()->willReturn(1);
    }

    public function it_returns_torrent_with_peers($torrent)
    {
        $torrent->getPeers()->willReturn(1);
    }

    public function it_returns_torrent_with_download_dir($torrent)
    {
        $torrent->getDownloadDir()->willReturn('ABC');
    }

    public function it_returns_torrent_with_files($torrent, $file)
    {
        $torrent->getFiles()->willReturn([$file]);
    }

    public function it_returns_file_with_completion($file)
    {
        $file->getCompleted()->willReturn(1);
    }

    public function it_returns_file_with_size($file)
    {
        $file->getSize()->willReturn(1);
    }

    public function it_returns_file_with_name($file)
    {
        $file->getName()->willReturn('ABC');
    }
}
