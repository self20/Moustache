<?php

declare(strict_types=1);

namespace TorrentBundle\Helper;

use Symfony\Component\Filesystem\Filesystem;
use TorrentBundle\Exception\BadTorrentStorageException;

class TorrentStorageHelper implements HelperGetterInterface
{
    /**
     * @var AuthenticatedUserHelper
     */
    private $authenticatedUserHelper;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $torrentStorage;

    /**
     * @var string
     */
    private $generatedPath;

    /**
     * @param AuthenticatedUserHelper $authenticatedUserHelper
     * @param Filesystem              $filesystem
     * @param string                  $torrentStorage
     */
    public function __construct(AuthenticatedUserHelper $authenticatedUserHelper, Filesystem $filesystem, string $torrentStorage)
    {
        $this->authenticatedUserHelper = $authenticatedUserHelper;
        $this->filesystem = $filesystem;
        $this->torrentStorage = $torrentStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return empty($this->torrentStorage);
    }

    public function getWhenAvailable()
    {
        $this->generatedPath = $this->generatePath();

        return $this->generatedPath;
    }

    /**
     * @throws BadTorrentStorageException
     *
     * @return string
     */
    public function get(): string
    {
        $this->generatedPath = $this->generatePath();

        if (!$this->isValid()) {
            $processUser = posix_getpwuid(posix_geteuid());
            throw new BadTorrentStorageException(sprintf(
                'Torrent storage directory seems to be invalid. Please, check that the “%s” directory is a absolute path readable and writable with system user “%s”.', $this->generatedPath, $processUser['name']
            ));
        }

        return $this->generatedPath;
    }

    private function isValid(): bool
    {
        return
            $this->filesystem->isAbsolutePath($this->generatedPath) &&
            $this->filesystem->exists($this->generatedPath) &&
            is_readable($this->generatedPath) &&
            is_writable($this->generatedPath)
        ;
    }

    private function generatePath(): string
    {
        return preg_replace('#\:username\:#', $this->authenticatedUserHelper->get()->getUsername(), $this->torrentStorage);
    }
}
