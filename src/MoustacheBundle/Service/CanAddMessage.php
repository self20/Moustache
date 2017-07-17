<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

interface CanAddMessage
{
    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function addErrorMessage(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function addInfoMessage(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function addSuccessMessage(string $message, ...$parameters);

    /**
     * @param string $message
     * @param mixed  $parameters
     */
    public function addWarnMessage(string $message, ...$parameters);
}
