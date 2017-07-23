<?php

declare(strict_types=1);

namespace TorrentBundle\Exception;

trait LoggableTrait
{
    /**
     * @var int
     */
    private $logLevel;

    /**
     * @var string
     */
    private $logMessage;

    /**
     * @param int $level
     */
    public function setLogLevel(int $level)
    {
        $this->logLevel = $level;
    }

    /**
     * @param string $message
     */
    public function setLogMessage(string $message)
    {
        $this->logMessage = $message;
    }
}
