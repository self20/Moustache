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
        return $this->diskSpaceReader->getVirtualFreeSpace() > self::MINIMUM_FREE_SPACE_ALLOWED;
    }
}
