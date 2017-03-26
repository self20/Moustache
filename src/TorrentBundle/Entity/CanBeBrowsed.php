<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

interface CanBeBrowsed
{
    /**
     * @return string
     */
    public function getFullPath(): string;

    /**
     * @return bool
     */
    public function isFile(): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getFriendlyName(): string;

    /**
     * @return string|null
     */
    public function getDownloadDir();

    /**
     * @return string
     */
    public function getMime(): string;

    /**
     * @param string $name
     */
    public function setName(string $name);

    /**
     * @param string $friendlyName
     */
    public function setFriendlyName(string $friendlyName);

    /**
     * @param string $downloadDir
     */
    public function setDownloadDir(string $downloadDir);

    /**
     * @param string $mime
     */
    public function setMime(string $mime);
}
