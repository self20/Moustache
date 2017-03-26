<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Filesystem\Filesystem;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Exception\BadTorrentStorageException;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Helper\TorrentStorageHelper;

class TorrentStorageHelperSpec extends ObjectBehavior
{
    public function let(
        AuthenticatedUserHelper $authenticatedUserHelper,
        Filesystem $filesystem,

        UserInterface $user
    ) {
        $user->getUsername()->willReturn('name');
        $authenticatedUserHelper->get()->willReturn($user);

        $filesystem->isAbsolutePath(Argument::type('string'))->willReturn(true);
        $filesystem->exists(Argument::type('string'))->willReturn(true);

        $this->beConstructedWith($authenticatedUserHelper, $filesystem, '/path/to/:username:/');
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

    public function it_throws_an_exception_when_storage_path_is_not_absolute($filesystem)
    {
        $filesystem->isAbsolutePath('/path/to/name/')->willReturn(false);

        $this->shouldThrow(BadTorrentStorageException::class)->during('get');
    }

    public function it_throws_an_exception_when_storage_path_does_not_exist($filesystem)
    {
        $filesystem->exists('/path/to/name/')->willReturn(false);

        $this->shouldThrow(BadTorrentStorageException::class)->during('get');
    }

    public function it_throws_an_exception_when_storage_path_is_not_readable($filesystem)
    {
        $this->shouldThrow(BadTorrentStorageException::class)->during('get');
    }

    public function it_returns_generated_path($authenticatedUserHelper, $filesystem)
    {
        $this->beConstructedWith($authenticatedUserHelper, $filesystem, __FILE__);

        $this->get()->shouldReturn(__FILE__);
    }
}
