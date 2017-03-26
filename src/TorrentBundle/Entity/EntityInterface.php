<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

interface EntityInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId(int $id);
}
