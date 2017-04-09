<?php

declare(strict_types=1);

namespace TorrentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TorrentClientPass implements CompilerPassInterface
{
    const NAME_TAG = 'type';
    const COLLECTOR_SERVICE = 'torrent.helper.torrent_client';
    const COLLECTOR_TAG = 'torrent.client';
    const COLLECTOR_METHOD = 'addTorrentClient';

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
                $definition->addMethodCall(self::COLLECTOR_METHOD, [new Reference($id), $attributes[self::NAME_TAG]]);
            }, $tags);
        }
    }
}
