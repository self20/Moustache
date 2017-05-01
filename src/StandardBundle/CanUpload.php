<?php

declare(strict_types=1);

namespace StandardBundle;

interface CanUpload
{
    /**
     * @return string
     */
    public function getUploadHumanRate(): string;

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
