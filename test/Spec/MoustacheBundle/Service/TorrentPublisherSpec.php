<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Service;

use MoustacheBundle\Exception\Permission\DownloadPermissionException;
use MoustacheBundle\Service\TorrentLinkGeneratorInterface;
use MoustacheBundle\Service\TorrentPublisher;
use MoustacheBundle\Service\TorrentPublisherInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Service\MimeGuesser;

class TorrentPublisherSpec extends ObjectBehavior
{
    public function let(
        Filesystem $filesystem,
        TorrentLinkGeneratorInterface $torrentLinkGenerator,

        TorrentInterface $torrent
    ) {
        $filesystem->symlink('/torrent/full/path', '/absolute/link')->willReturn(null);

        $torrentLinkGenerator->generateAbsoluteLink($torrent)->willReturn('/absolute/link');
        $torrentLinkGenerator->generateWebLink($torrent)->willReturn('web/link');

        $torrent->isFile()->willReturn(true);
        $torrent->getMime()->willReturn(MimeGuesser::MIME_AUDIO);
        $torrent->getFullPath()->willReturn('/torrent/full/path');

        $this->beConstructedWith($filesystem, $torrentLinkGenerator, true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentPublisher::class);
    }

    public function it_is_a_torrent_publisher()
    {
        $this->shouldImplement(TorrentPublisherInterface::class);
    }

    public function it_throws_an_exception_if_download_has_been_disabled_administratively($filesystem, $torrentLinkGenerator, $torrent)
    {
        $this->beConstructedWith($filesystem, $torrentLinkGenerator, false);

        $this->shouldThrow(DownloadPermissionException::class)->during('publish', [$torrent]);
    }

    public function it_throws_an_exception_if_torrent_is_not_a_file($torrent)
    {
        $torrent->isFile()->willReturn(false);

        $this->shouldThrow(DownloadPermissionException::class)->during('publish', [$torrent]);
    }

    public function it_throws_an_exception_if_torrent_has_unsafe_extension($torrent)
    {
        $torrent->getMime()->willReturn(MimeGuesser::MIME_OTHER);

        $this->shouldThrow(DownloadPermissionException::class)->during('publish', [$torrent]);
    }

    public function it_generates_a_downloadable_public_link($filesystem, $torrent)
    {
        $filesystem->symlink('/torrent/full/path', '/absolute/link')->shouldBeCalledTimes(1);

        $this->publish($torrent);
    }

    public function it_returns_a_public_web_link($torrent)
    {
        $this->publish($torrent)->shouldReturn('web/link');
    }
}
