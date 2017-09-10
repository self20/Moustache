<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Message;

use MoustacheBundle\Message\Handler\MessageHandlerInterface;
use MoustacheBundle\Message\MessageHandlerCollection;
use PhpSpec\ObjectBehavior;

class MessageHandlerCollectionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(MessageHandlerCollection::class);
    }

    public function it_adds_and_gets_message_handlers(MessageHandlerInterface $messageHandler, MessageHandlerInterface $messageHandler2)
    {
        $this->addMessageHandler($messageHandler);
        $this->addMessageHandler($messageHandler2);

        $this->getAll()->shouldReturn([$messageHandler, $messageHandler2]);
    }
}
