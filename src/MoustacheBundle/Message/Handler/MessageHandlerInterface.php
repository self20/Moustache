<?php

declare(strict_types=1);

namespace MoustacheBundle\Message\Handler;

interface MessageHandlerInterface
{
    const TYPE_ERROR = 'danger';
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARN = 'warning';

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function error(string $message, string ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function info(string $message, string ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function success(string $message, string ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function warn(string $message, string ...$parameters);
}
