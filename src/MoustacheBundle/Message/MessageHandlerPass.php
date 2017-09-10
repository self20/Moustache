<?php

declare(strict_types=1);

namespace MoustacheBundle\Message;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MessageHandlerPass implements CompilerPassInterface
{
    const COLLECTOR_SERVICE = MessageHandlerCollection::class;
    const COLLECTOR_TAG = 'message.handler';
    const COLLECTOR_METHOD = 'addMessageHandler';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::COLLECTOR_SERVICE)) {
            return;
        }

        $definition = $container->findDefinition(self::COLLECTOR_SERVICE);
        $taggedServices = $container->findTaggedServiceIds(self::COLLECTOR_TAG);

        foreach ($taggedServices as $id => $tags) {
            array_map(function ($attributes) use ($id, $definition) {
                $definition->addMethodCall(self::COLLECTOR_METHOD, [new Reference($id)]);
            }, $tags);
        }
    }
}
