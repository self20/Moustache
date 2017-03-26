<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

interface FlashMessengerInterface
{
    const TYPE_ERROR = 'danger';
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARN = 'warn';

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function error(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function info(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function success(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function warn(string $message, ...$parameters);
}
