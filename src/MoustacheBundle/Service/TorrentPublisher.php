<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use MoustacheBundle\Exception\Permission\DownloadPermissionException;
use StandardBundle\TorrentInterface;
use Symfony\Component\Filesystem\Filesystem;
use TorrentBundle\Service\MimeGuesser;

/**
 * Makes torrent files public.
 */
class TorrentPublisher implements TorrentPublisherInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var TorrentLinkGeneratorInterface
     */
    private $torrentLinkGenerator;

    /**
     * @var bool
     */
    private $allowDirectDownload;

    /**
     * @param Filesystem                    $filesystem
     * @param TorrentLinkGeneratorInterface $torrentLinkGenerator
     * @param bool                          $allowDirectDownload
     */
    public function __construct(Filesystem $filesystem, TorrentLinkGeneratorInterface $torrentLinkGenerator, bool $allowDirectDownload)
    {
        $this->filesystem = $filesystem;
        $this->torrentLinkGenerator = $torrentLinkGenerator;
        $this->allowDirectDownload = $allowDirectDownload;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(TorrentInterface $torrent): string
    {
        $this->checkDownloadPermissions($torrent);

        $this->filesystem->symlink($torrent->getFullPath(), $this->torrentLinkGenerator->generateAbsoluteLink($torrent));
        $this->filesystem->touch($torrent->getFullPath(), null, time());

        return $this->torrentLinkGenerator->generateWebLink($torrent);
    }

    private function checkDownloadPermissions(TorrentInterface $torrent)
    {
        if (!$this->allowDirectDownload) {
            throw new DownloadPermissionException('Direct download of files have been administratively prohibited.');
        }

        if (!$torrent->isFile()) {
            throw new DownloadPermissionException('This torrent cannot be downloaded because it‘s a directory.');
        }

        if (!$this->hasSafeExtension($torrent)) {
            throw new DownloadPermissionException('This torrent cannot be downloaded because its extension may be dangerous.');
        }
    }

    private function hasSafeExtension(TorrentInterface $torrent): bool
    {
        return
            MimeGuesser::MIME_VIDEO === $torrent->getMime() ||
            MimeGuesser::MIME_AUDIO === $torrent->getMime() ||
            MimeGuesser::MIME_IMAGE === $torrent->getMime() ||
            MimeGuesser::MIME_ISO === $torrent->getMime() ||
            MimeGuesser::MIME_ARCHIVE === $torrent->getMime() ||
            MimeGuesser::MIME_PDF === $torrent->getMime()
        ;
    }
}
