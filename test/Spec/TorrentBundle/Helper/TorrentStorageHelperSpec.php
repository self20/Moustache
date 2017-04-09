<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Exception\Configuration\BadTorrentStorageException;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Helper\TorrentStorageHelper;

class TorrentStorageHelperSpec extends ObjectBehavior
{
    public function let(
        AuthenticatedUserHelper $authenticatedUserHelper,
        Filesystem $filesystem,
        LoggerInterface $logger,

        UserInterface $user
    ) {
        $user->getUsername()->willReturn('name');
        $authenticatedUserHelper->get()->willReturn($user);

        $filesystem->isAbsolutePath(Argument::type('string'))->willReturn(true);
        $filesystem->exists(Argument::type('string'))->willReturn(true);

        $logger->info(Argument::type('string'))->willReturn(null);

        $this->beConstructedWith($authenticatedUserHelper, $filesystem, $logger, '/path/to/:username:/');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentStorageHelper::class);
    }

    public function it_tells_if_torrent_storage_string_is_empty()
    {
        $this->isEmpty()->shouldReturn(false);
    }

    public function it_returns_generated_path_without_any_check()
    {
        $this->getWhenAvailable()->shouldReturn('/path/to/name/');
    }

    public function it_creates_the_path_if_it_does_not_exists($authenticatedUserHelper, $logger, $filesystem)
    {
        $this->beConstructedWith($authenticatedUserHelper, $filesystem, $logger, __FILE__);

        $filesystem->exists(__FILE__)->willReturn(false);

        $filesystem->mkdir(__FILE__, 0770)->shouldBeCalledTimes(1);

        $this->get();
    }

    public function it_throws_an_exception_when_storage_path_is_not_absolute($filesystem)
    {
        $filesystem->isAbsolutePath('/path/to/name/')->willReturn(false);

        $this->shouldThrow(BadTorrentStorageException::class)->during('get');
    }

    public function it_throws_an_exception_when_storage_path_is_not_readable($filesystem)
    {
        $this->shouldThrow(BadTorrentStorageException::class)->during('get');
    }

    public function it_returns_generated_path($authenticatedUserHelper, $logger, $filesystem)
    {
        $this->beConstructedWith($authenticatedUserHelper, $filesystem, $logger, __FILE__);

        $this->get()->shouldReturn(__FILE__);
    }
}
