<?php

declare(strict_types=1);

namespace TorrentBundle\DiskSpace;

class DiskSpaceChecker
{
    const MINIMUM_FREE_SPACE_ALLOWED = 128000000;

    /**
     * @var DiskSpaceReader
     */
    private $diskSpaceReader;

    /**
     * @var int
     */
    private $lastComputedVirtualFreeSpace = 0;

    /**
     * @param DiskSpaceReader $diskSpaceReader
     */
    public function __construct(DiskSpaceReader $diskSpaceReader)
    {
        $this->diskSpaceReader = $diskSpaceReader;
    }

    /**
     * @return bool
     */
    public function checkEnoughDiskSpace(): bool
    {
        $this->lastComputedVirtualFreeSpace = $this->diskSpaceReader->getVirtualFreeSpace();

        return $this->lastComputedVirtualFreeSpace > self::MINIMUM_FREE_SPACE_ALLOWED;
    }

    /**
     * @return int
     */
    public function getLastComputedVirtualFreeSpace(): int
    {
        return $this->lastComputedVirtualFreeSpace;
    }
}
