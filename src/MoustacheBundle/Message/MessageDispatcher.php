<?php

declare(strict_types=1);

namespace MoustacheBundle\Message;

class MessageDispatcher implements CanDispatchMessage
{
    /**
     * @var MessageHandlerCollection
     */
    private $messageHandlerCollection;

    /**
     * @param MessageHandlerCollection $messageHandlerCollection
     */
    public function __construct(MessageHandlerCollection $messageHandlerCollection)
    {
        $this->messageHandlerCollection = $messageHandlerCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function error(array $messages, ...$args)
    {
        $this->dispatch(__FUNCTION__, $messages, ...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function info(array $messages, ...$args)
    {
        $this->dispatch(__FUNCTION__, $messages, ...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function success(array $messages, ...$args)
    {
        $this->dispatch(__FUNCTION__, $messages, ...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function warn(array $messages, ...$args)
    {
        $this->dispatch(__FUNCTION__, $messages, ...$args);
    }

    private function dispatch(string $method, array $messages, ...$args)
    {
        foreach ($this->messageHandlerCollection->getAll() as $dispatcher) {
            if (!isset($messages[get_class($dispatcher)]) || !method_exists($dispatcher, $method)) {
                continue;
            }
            $dispatcher->$method($messages[get_class($dispatcher)], ...$args);
        }
    }
}
