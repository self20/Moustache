<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

interface CanUpload
{
    /**
     * @return bool
     */
    public function isUploading(): bool;

    // ---

    /**
     * @return int
     */
    public function getUploadRate(): int;

    /**
     * @param int $uploadRate
     */
    public function setUploadRate(int $uploadRate);
}
