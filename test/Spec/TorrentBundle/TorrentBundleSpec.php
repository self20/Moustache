<?php

declare(strict_types=1);

namespace Spec\TorrentBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TorrentBundle\DependencyInjection\TorrentClientPass;
use TorrentBundle\TorrentBundle;

class TorrentBundleSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentBundle::class);
    }

    public function it_builds_itself_with_a_torrent_client_pass(ContainerBuilder $container)
    {
        $container->addCompilerPass(Argument::type(TorrentClientPass::class))->shouldBeCalledTimes(1);

        $this->build($container);
    }
}
