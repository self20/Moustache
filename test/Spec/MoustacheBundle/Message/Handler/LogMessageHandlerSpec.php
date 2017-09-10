<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Message\Handler;

use MoustacheBundle\Message\Handler\LogMessageHandler;
use MoustacheBundle\Message\Handler\MessageHandlerInterface;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class LogMessageHandlerSpec extends ObjectBehavior
{
    public function let(LoggerInterface $logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LogMessageHandler::class);
    }

    public function it_is_a_message_handler()
    {
        $this->shouldHaveType(MessageHandlerInterface::class);
    }

    public function it_logs_an_error_message($logger)
    {
        $logger->error('message')->shouldBeCalledTimes(1);

        $this->error('message');
    }

    public function it_logs_a_warning_message($logger)
    {
        $logger->warning('message WARNING')->shouldBeCalledTimes(1);

        $this->warn('message %s', 'WARNING');
    }

    public function it_logs_a_success_message($logger)
    {
        $logger->info('success INFO PARAM')->shouldBeCalledTimes(1);

        $this->success('success %s %s', 'INFO', 'PARAM');
    }

    public function it_logs_a_info_message($logger)
    {
        $logger->info('info')->shouldBeCalledTimes(1);

        $this->info('info', 'PARAM');
    }
}
