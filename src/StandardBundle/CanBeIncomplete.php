<?php

declare(strict_types=1);

namespace StandardBundle;

interface CanBeIncomplete
{
    /**
     * @return string
     */
    public function getCurrentHumanSize(): string;

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
    public function isCompleted(): bool;

    // ---

    /**
     * @return int|null
     */
    public function getCurrentByteSize();

    /**
     * @return int|null
     */
    public function getTotalByteSize();

    // ---

    /**
     * @param int $currentByteSize
     */
    public function setCurrentByteSize(int $currentByteSize = null);

    /**
     * @param int $totalByteSize
     */
    public function setTotalByteSize(int $totalByteSize = null);
}
