<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Event\TorrentAfterEvent;
use TorrentBundle\EventListener\UploadedFileRemoverListener;

class UploadedFileRemoverListenerSpec extends ObjectBehavior
{
    public function let(
        Filesystem $filessystem,

        TorrentAfterEvent $event,
        TorrentInterface $torrent,
        \SplFileInfo $uploadedFile
    ) {
        $uploadedFile->getRealPath()->willReturn('/path/real');
        $torrent->getUploadedFile()->willReturn($uploadedFile);
        $torrent->setUploadedFileByUrl(null)->willReturn(null);
        $torrent->setUploadedFile(null)->willReturn(null);
        $event->getTorrent()->willReturn($torrent);

        $filessystem->remove('/path/real')->willReturn(null);

        $this->beConstructedWith($filessystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UploadedFileRemoverListener::class);
    }

    public function it_does_nothing_if_uploaded_file_is_empty($filessystem, $event, $torrent)
    {
        $torrent->getUploadedFile()->willReturn(null);

        $filessystem->remove('/path/real')->shouldNotBeCalled();

        $this->afterTorrentAdded($event)->shouldReturn(null);
    }

    public function it_removes_the_uploaded_file($filessystem, $event)
    {
        $filessystem->remove('/path/real')->shouldBeCalledTimes(1);

        $this->afterTorrentAdded($event)->shouldReturn($event);
    }

    public function it_resets_torrent_uploaded_file_to_null_state($torrent, $event)
    {
        $torrent->setUploadedFile(null)->shouldBeCalledTimes(1);
        $torrent->setUploadedFileByUrl(null)->shouldBeCalledTimes(1);

        $this->afterTorrentAdded($event)->shouldReturn($event);
    }
}
