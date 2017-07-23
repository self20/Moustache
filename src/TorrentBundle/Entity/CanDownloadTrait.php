<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use Rico\Slib\StringUtils;
use StandardBundle\CanDownload;

trait CanDownloadTrait
{
    /**
     * @var int
     */
    protected $downloadRate = 0;

    /**
     * @var int
     */
    protected $status = CanDownload::STATUS_STOP;

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
    public function isDownloading(): bool
    {
        return $this->downloadRate > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted(): bool
    {
        return CanDownload::STATUS_STOP !== $this->status;
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

    // ---

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
}
