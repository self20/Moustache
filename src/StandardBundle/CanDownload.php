<?php

declare(strict_types=1);

namespace StandardBundle;

interface CanDownload extends CanBeIncomplete
{
    const STATUS_FAIL = -1;
    const STATUS_STOP = 0;
    const STATUS_DOWNLOADING = 4;
    const STATUS_DONE = 6;

    /**
     * @return string
     */
    public function getDownloadHumanRate(): string;

    /**
     * @return bool
     */
    public function isDownloading(): bool;

    /**
     * @return bool
     */
    public function isStarted(): bool;

    /**
     * @return bool
     */
    public function isStopped(): bool;

    // ---

    /**
     * @return int
     */
    public function getDownloadRate(): int;

    /**
     * @return int
     */
    public function getStatus(): int;

    // ---

    /**
     * @param int $downloadRate
     */
    public function setDownloadRate(int $downloadRate = null);

    /**
     * @param int $status
     */
    public function setStatus(int $status);
}
