<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

interface CanAddMessage
{
    /**
     * @param string $message
     * @param mixed  $parameters
     * @param mixed  $...
     */
    public function addErrorMessage(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     * @param mixed  $...
     */
    public function addInfoMessage(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     * @param mixed  $...
     */
    public function addSuccessMessage(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     * @param mixed  $...
     */
    public function addWarnMessage(string $message, ...$parameters);
}
