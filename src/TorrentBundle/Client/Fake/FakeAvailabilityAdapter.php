<?php

declare(strict_types=1);

namespace TorrentBundle\Adapter\Fake;

use TorrentBundle\Adapter\CanBeAvailable;

class FakeAvailabilityAdapter implements CanBeAvailable
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return true;
    }
}
