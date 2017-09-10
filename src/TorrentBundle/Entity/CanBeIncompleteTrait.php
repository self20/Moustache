<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use Rico\Slib\StringUtils;

trait CanBeIncompleteTrait
{
    /**
     * @var int
     */
    protected $currentByteSize = 0;

    /**
     * @var int
     */
    protected $totalByteSize = 0;

    /**
     * {@inheritdoc}
     */
    public function getCurrentHumanSize(): string
    {
        return StringUtils::humanFilesize($this->currentByteSize);
    }

    /**
     * {@inheritdoc}
     */
    public function getPercentDone(): float
    {
        if (0 === $this->totalByteSize) {
            return 0;
        }

        return round(($this->currentByteSize / $this->totalByteSize) * 100, 2);
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
    public function getVirtualUsedByteSize(): int
    {
        return $this->totalByteSize - $this->currentByteSize;
    }

    /**
     * {@inheritdoc}
     */
    public function isCompleted(): bool
    {
        return$this->currentByteSize === $this->totalByteSize;
    }

    // ---

    /**
     * {@inheritdoc}
     */
    public function getCurrentByteSize(): int
    {
        return $this->currentByteSize;
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
    public function setCurrentByteSize(int $currentByteSize = null)
    {
        $this->currentByteSize = $currentByteSize ?? 0;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalByteSize(int $totalByteSize = null)
    {
        $this->totalByteSize = $totalByteSize ?? 0;
    }
}
