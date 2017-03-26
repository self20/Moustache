<?php

declare(strict_types=1);

namespace TorrentBundle\Helper;

interface HelperSetterInterface
{
    /**
     * @param \stdClass $object
     */
    public function set($object);
}
