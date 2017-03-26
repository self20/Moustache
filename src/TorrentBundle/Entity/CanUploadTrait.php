<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

trait CanUploadTrait
{
    /**
     * @var int
     */
    protected $uploadRate = 0;

    /**
     * {@inheritdoc}
     */
    public function isUploading(): bool
    {
        return $this->uploadRate > 0;
    }

    // ---

    /**
     * {@inheritdoc}
     */
    public function getUploadRate(): int
    {
        return $this->uploadRate;
    }

    /**
     * {@inheritdoc}
     */
    public function setUploadRate(int $uploadRate = null)
    {
        $this->uploadRate = $uploadRate ?? 0;
    }
}
