<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

interface CanBeUploaded
{
    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return \SplFileInfo|null
     */
    public function getUploadedFile();

    /**
     * @param string $url
     */
    public function setUrl(string $url = null);

    /**
     * @param \SplFileInfo $uploadedFile
     */
    public function setUploadedFile(\SplFileInfo $uploadedFile = null);
}
