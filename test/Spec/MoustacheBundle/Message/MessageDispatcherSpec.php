<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Message;

use MoustacheBundle\Message\CanDispatchMessage;
use MoustacheBundle\Message\Handler\MessageHandlerInterface;
use MoustacheBundle\Message\MessageDispatcher;
use MoustacheBundle\Message\MessageHandlerCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MessageDispatcherSpec extends ObjectBehavior
{
    public function let(
        MessageHandlerCollection $messageHandlerCollection,

        MessageHandlerInterface $messageHandler,
        MessageHandlerInterface $messageHandler2
    ) {
        $messageHandlerCollection->getAll()->willReturn([$messageHandler, $messageHandler2]);

        $this->beConstructedWith($messageHandlerCollection);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MessageDispatcher::class);
    }

    public function it_can_dispatch_messages()
    {
        $this->shouldHaveType(CanDispatchMessage::class);
    }

    public function it_dispatches_error_messages($messageHandler, $messageHandler2)
    {
        $messageHandler->error('message', 'param')->shouldBeCalledTimes(1);
        $messageHandler2->error(Argument::cetera())->shouldNotBeCalled();

        $this->error([get_class($messageHandler->getWrappedObject()) => 'message'], 'param');
    }

    public function it_dispatches_warning_messages($messageHandler, $messageHandler2)
    {
        $messageHandler->warn(Argument::cetera())->shouldNotBeCalled();
        $messageHandler2->warn('message', 'param')->shouldBeCalledTimes(1);

        $this->warn([get_class($messageHandler2->getWrappedObject()) => 'message'], 'param');
    }

    public function it_dispatches_success_messages($messageHandler, $messageHandler2)
    {
        $messageHandler->success('message 1', 'param')->shouldBeCalledTimes(1);
        $messageHandler2->success('message 2', 'param')->shouldBeCalledTimes(1);

        $this->success([get_class($messageHandler->getWrappedObject()) => 'message 1', get_class($messageHandler2->getWrappedObject()) => 'message 2'], 'param');
    }

    public function it_dispatches_info_messages($messageHandler, $messageHandler2)
    {
        $messageHandler->info('message', 'param')->shouldBeCalledTimes(1);
        $messageHandler2->info(Argument::cetera())->shouldNotBeCalled();

        $this->info([get_class($messageHandler->getWrappedObject()) => 'message'], 'param');
    }
}
