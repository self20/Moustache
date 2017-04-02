<?php

declare(strict_types=1);

namespace TorrentBundle\EventListener;

use Symfony\Component\Filesystem\Filesystem;
use TorrentBundle\Event\TorrentAfterEvent;

/**
 * Removes a temporary file after it’s been used.
 */
final class UploadedFileRemoverListener
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param TorrentAfterEvent $event
     */
    public function afterTorrentAdded(TorrentAfterEvent $event)
    {
        if (!$event->getTorrent()->getUploadedFile()) {
            return;
        }

        $this->filesystem->remove($event->getTorrent()->getUploadedFile()->getRealPath());
        $event->getTorrent()->setUploadedFile(null);
        $event->getTorrent()->setUploadedFileByUrl(null);

        return $event;
    }
}
