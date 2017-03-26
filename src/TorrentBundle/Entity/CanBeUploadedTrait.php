<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

trait CanBeUploadedTrait
{
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var \SplFileInfo
     */
    protected $uploadedFile = null;

    /**
     * {@inheritdoc}
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl(string $url = null)
    {
        $this->url = $url ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function setUploadedFile(\SplFileInfo $uploadedFile = null)
    {
        $this->uploadedFile = $uploadedFile;
    }
}
