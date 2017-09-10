<?php

declare(strict_types=1);

namespace Spec\MoustacheBundle;

use MoustacheBundle\Message\MessageHandlerPass;
use MoustacheBundle\MoustacheBundle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MoustacheBundleSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(MoustacheBundle::class);
    }

    public function it_builds_itself_with_a_message_handler_pass(ContainerBuilder $container)
    {
        $container->addCompilerPass(Argument::type(MessageHandlerPass::class))->shouldBeCalledTimes(1);

        $this->build($container);
    }
}
