<?php

declare(strict_types=1);

namespace MoustacheBundle\Message\Handler;

use Psr\Log\LoggerInterface;

class LogMessageHandler implements MessageHandlerInterface
{
    use MessageBuilderTrait;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function error(string $message, string ...$parameters)
    {
        $this->logger->error($this->buildMessage($message, ...$parameters));
    }

    /**
     * {@inheritdoc}
     */
    public function info(string $message, string ...$parameters)
    {
        $this->logger->info($this->buildMessage($message, ...$parameters));
    }

    /**
     * {@inheritdoc}
     */
    public function success(string $message, string ...$parameters)
    {
        $this->logger->info($this->buildMessage($message, ...$parameters));
    }

    /**
     * {@inheritdoc}
     */
    public function warn(string $message, string ...$parameters)
    {
        $this->logger->warning($this->buildMessage($message, ...$parameters));
    }
}
