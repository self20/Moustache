<?php

declare(strict_types=1);

namespace TorrentBundle\Exception\Permission;

class NotEnoughDiskSpaceException extends PermissionException
{
    /**
     * @var string
     */
    private $neededSpace;

    /**
     * @var string
     */
    private $availableSpace;

    // ---

    /**
     * @return string
     */
    public function getNeededSpace(): string
    {
        return $this->neededSpace;
    }

    /**
     * @return string
     */
    public function getAvailableSpace(): string
    {
        return $this->availableSpace;
    }

    // ---

    /**
     * @param string $neededSpace
     */
    public function setNeededSpace(string $neededSpace)
    {
        $this->neededSpace = $neededSpace;
    }

    /**
     * @param string $availableSpace
     */
    public function setAvailableSpace(string $availableSpace)
    {
        $this->availableSpace = $availableSpace;
    }
}
