<?php

declare(strict_types=1);

namespace MoustacheBundle;

use MoustacheBundle\Message\MessageHandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MoustacheBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MessageHandlerPass());
    }
}
