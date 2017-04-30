<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use Rico\Slib\StringUtils;

trait CanDownloadTrait
{
    /**
     * @var int
     */
    protected $downloadedByteSize = 0;

    /**
     * @var int
     */
    protected $downloadRate = 0;

    /**
     * @var int
     */
    protected $status = CanDownload::STATUS_STOP;

    /**
     * @var int
     */
    protected $totalByteSize = 0;

    /**
     * {@inheritdoc}
     */
    public function getDownloadedHumanSize(): string
    {
        return StringUtils::humanFilesize($this->downloadedByteSize);
    }

    /**
     * {@inheritdoc}
     */
    public function getDownloadHumanRate(): string
    {
        return StringUtils::humanFilesize($this->downloadRate);
    }

    /**
     * {@inheritdoc}
     */
    public function getPercentDone(): float
    {
        if ($this->totalByteSize === 0) {
            return 0;
        }

        return round(($this->downloadedByteSize / $this->totalByteSize) * 100, 2);
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalHumanSize(): string
    {
        return StringUtils::humanFilesize($this->totalByteSize);
    }

    /**
     * {@inheritdoc}
     */
    public function isDone(): bool
    {
        return$this->downloadedByteSize === $this->totalByteSize;
    }

    /**
     * {@inheritdoc}
     */
    public function isDownloading(): bool
    {
        return $this->downloadRate > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isStopped(): bool
    {
        return CanDownload::STATUS_STOP === $this->status;
    }

    // ---

    /**
     * {@inheritdoc}
     */
    public function getDownloadedByteSize(): int
    {
        return $this->downloadedByteSize;
    }

    /**
     * {@inheritdoc}
     */
    public function getDownloadRate(): int
    {
        return $this->downloadRate;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalByteSize(): int
    {
        return $this->totalByteSize;
    }

    // ---

    /**
     * {@inheritdoc}
     */
    public function setDownloadedByteSize(int $downloadedByteSize = null)
    {
        $this->downloadedByteSize = $downloadedByteSize ?? 0;
    }

    /**
     * {@inheritdoc}
     */
    public function setDownloadRate(int $downloadRate = null)
    {
        $this->downloadRate = $downloadRate ?? 0;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalByteSize(int $totalByteSize = null)
    {
        $this->totalByteSize = $totalByteSize ?? 0;
    }
}
