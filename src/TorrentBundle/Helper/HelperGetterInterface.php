<?php

declare(strict_types=1);

namespace TorrentBundle\Helper;

interface HelperGetterInterface
{
    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return mixed
     */
    public function getWhenAvailable();

    /**
     * It will throw an exception if object is not found.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function get();
}
