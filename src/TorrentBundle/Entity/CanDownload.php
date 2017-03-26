<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

interface CanDownload
{
    const STATUS_STOP = 0;
    const STATUS_DOWNLOADING = 4;
    const STATUS_DONE = 6;

    /**
     * @return string
     */
    public function getDownloadedHumanSize(): string;

    /**
     * @return string
     */
    public function getPercentDone(): float;

    /**
     * @return string
     */
    public function getTotalHumanSize(): string;

    /**
     * @return bool
     */
    public function isDone(): bool;

    /**
     * @return bool
     */
    public function isDownloading(): bool;

    /**
     * @return bool
     */
    public function isStopped(): bool;

    // ---

    /**
     * @return int|null
     */
    public function getDownloadedByteSize();

    /**
     * @return int
     */
    public function getDownloadRate(): int;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @return int|null
     */
    public function getTotalByteSize();

    // ---

    /**
     * @param int $downloadedByteSize
     */
    public function setDownloadedByteSize(int $downloadedByteSize = null);

    /**
     * @param int $downloadRate
     */
    public function setDownloadRate(int $downloadRate = null);

    /**
     * @param int $status
     */
    public function setStatus(int $status);

    /**
     * @param int $totalByteSize
     */
    public function setTotalByteSize(int $totalByteSize = null);
}
