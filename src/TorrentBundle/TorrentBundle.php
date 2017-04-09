<?php

declare(strict_types=1);

namespace TorrentBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TorrentBundle\DependencyInjection\TorrentClientPass;

class TorrentBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TorrentClientPass());
    }
}
