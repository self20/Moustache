<?php

declare(strict_types=1);

namespace MoustacheBundle\Message;

use MoustacheBundle\Message\Handler\FlashMessageHandler;
use MoustacheBundle\Message\Handler\LogMessageHandler;

interface CanDispatchMessage
{
    const TORRENT_IS_MISSING = [
        LogMessageHandler::class => 'A torrent with hash “%s” was requested but it was not found by “%s” client. It may have been removed manually in %s but still exists in moustache database or cache may has been temporarly out of date. Please fix the problem, as it can lead to performance issues.',
        FlashMessageHandler::class => 'It seems one of your torrent have been deleted unexpectedly from the system.',
    ];

    const GREET_USER = [
        FlashMessageHandler::class => 'Hi folk! Here you can upload your .torrent files, wait a few seconds… then download the content to your computer. Sooo easy I could wax myself.',
    ];

    /**
     * @param string $messages
     * @param mixed  $parameters
     */
    public function error(array $messages, ...$parameters);

    /**
     * @param array $messages
     * @param mixed $parameters
     */
    public function info(array $messages, ...$parameters);

    /**
     * @param array $messages
     * @param mixed $parameters
     */
    public function success(array $messages, ...$parameters);

    /**
     * @param array $messages
     * @param mixed $parameters
     */
    public function warn(array $messages, ...$parameters);
}
