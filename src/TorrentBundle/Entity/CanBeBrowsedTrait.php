<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use TorrentBundle\Service\MimeGuesser;

trait CanBeBrowsedTrait
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $friendlyName = '';

    /**
     * @var string
     */
    protected $downloadDir;

    /**
     * @var string
     */
    protected $mime = MimeGuesser::MIME_OTHER;

    /**
     * {@inheritdoc}
     */
    public function getFullPath(): string
    {
        return sprintf('%s/%s', $this->downloadDir, $this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function isFile(): bool
    {
        return MimeGuesser::MIME_DIRECTORY !== $this->mime;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastAccessTimestamp(): int
    {
        clearstatcache(true, $this->getFullPath());

        return fileatime($this->getFullPath());
    }

    /**
     * {@inheritdoc}
     */
    public function getSecondsSinceLastAccess(): int
    {
        return time() - $this->getLastAccessTimestamp();
    }

    // ---

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getFriendlyName(): string
    {
        return $this->friendlyName;
    }

    /**
     * {@inheritdoc}
     */
    public function getDownloadDir()
    {
        return $this->downloadDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setFriendlyName(string $friendlyName)
    {
        $this->friendlyName = $friendlyName;
    }

    /**
     * {@inheritdoc}
     */
    public function setDownloadDir(string $downloadDir)
    {
        $this->downloadDir = $downloadDir;
    }

    /**
     * {@inheritdoc}
     */
    public function setMime(string $mime)
    {
        $this->mime = $mime;
    }
}
