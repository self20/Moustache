<?php

declare(strict_types=1);

namespace MoustacheBundle\Task;

use MoustacheBundle\Service\TorrentLinkGeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class CleanDownloadsTask implements TaskInterface
{
    const SECONDS_UNTIL_REMOVAL = 120;

    /**
     * @var TorrentLinkGeneratorInterface
     */
    private $torrentLinkGenerator;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var int
     */
    private $cleanDownloadDelayInSeconds;

    /**
     * @var string[]
     */
    private $removedFiles = [];

    /**
     * @param TorrentLinkGeneratorInterface $torrentLinkGenerator
     * @param Filesystem                    $filesystem
     * @param int                           $cleanDownloadDelayInSeconds
     */
    public function __construct(TorrentLinkGeneratorInterface $torrentLinkGenerator, Filesystem $filesystem, int $cleanDownloadDelayInSeconds)
    {
        $this->torrentLinkGenerator = $torrentLinkGenerator;
        $this->filesystem = $filesystem;
        $this->cleanDownloadDelayInSeconds = $cleanDownloadDelayInSeconds;
    }

    /**
     * {@inheritdoc}
     */
    public function setup()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function teardown()
    {
        return $this->removedFiles;
    }

    /**
     * {@inheritdoc}
     */
    public function run(): int
    {
        $finder = new Finder();
        $finder->files()->in($this->torrentLinkGenerator->generatePartialAbsoluteLink());

        foreach ($finder as $file) {
            if ($this->hasExpired($file)) {
                $this->removedFiles[] = $file->getPathname();
                $this->filesystem->remove($file->getPathname());
                $this->filesystem->remove($file->getPath());
            }
        }

        return 0;
    }

    private function getSecondsSinceLastAccess(\SplFileInfo $file): int
    {
        return time() - $file->getATime();
    }

    private function hasExpired(\SplFileInfo $file): bool
    {
        return $this->getSecondsSinceLastAccess($file) >= $this->cleanDownloadDelayInSeconds;
    }
}
