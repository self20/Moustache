<?php

declare(strict_types=1);

namespace TorrentBundle\Exception;

interface LoggableException
{
    /**
     * @param int $level
     */
    public function setLogLevel(int $level);

    /**
     * @param string $message
     */
    public function setLogMessage(string $message);
}
