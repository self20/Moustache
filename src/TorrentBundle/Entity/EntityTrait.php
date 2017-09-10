<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

trait EntityTrait
{
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(int $id)
    {
        if (null !== $this->id) {
            return;
        }

        $this->id = $id;
    }
}
