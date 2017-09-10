<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle\Message;

use MoustacheBundle\Message\Handler\LogMessageHandler;
use MoustacheBundle\Message\MessageHandlerPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class MessageHandlerPassSpec extends ObjectBehavior
{
    public function let(ContainerBuilder $container, Definition $definition)
    {
        $container->findDefinition(MessageHandlerPass::COLLECTOR_SERVICE)->willReturn($definition);
        $container->findTaggedServiceIds(MessageHandlerPass::COLLECTOR_TAG)->willReturn([LogMessageHandler::class => ['name' => 'message.handler']]);

        $container->has(MessageHandlerPass::COLLECTOR_SERVICE)->willReturn(true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MessageHandlerPass::class);
    }

    public function it_is_a_compiler_pass()
    {
        $this->shouldHaveType(CompilerPassInterface::class);
    }

    public function it_does_nothing_if_not_message_handler_collection_exists($container, $definition)
    {
        $container->has(MessageHandlerPass::COLLECTOR_SERVICE)->willReturn(false);

        $definition->addMethodCall(Argument::cetera())->shouldNotBeCalled();

        $this->process($container)->shouldReturn(null);
    }

    public function it_adds_message_handler_definition_to_the_message_handler_collection($container, $definition)
    {
        $definition->addMethodCall(MessageHandlerPass::COLLECTOR_METHOD, Argument::type('array'))->shouldBeCalledTimes(1);

        $this->process($container);
    }
}
