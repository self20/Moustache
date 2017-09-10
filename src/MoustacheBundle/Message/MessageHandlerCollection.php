<?php

declare(strict_types=1);

namespace MoustacheBundle\Message;

use MoustacheBundle\Message\Handler\MessageHandlerInterface;

class MessageHandlerCollection
{
    /**
     * @var MessageHandlerInterface[]
     */
    private $messageHandlers;

    /**
     * @param MessageHandlerInterface $messageHandler
     */
    public function addMessageHandler(MessageHandlerInterface $messageHandler)
    {
        $this->messageHandlers[] = $messageHandler;
    }

    /**
     * @return MessageHandlerInterface[]
     */
    public function getAll(): array
    {
        return $this->messageHandlers;
    }
}
