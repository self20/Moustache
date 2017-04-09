<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rico\Lib\FileUtils;
use TorrentBundle\Service\MimeGuesser;

class MimeGuesserSpec extends ObjectBehavior
{
    public function let(FileUtils $fileUtils)
    {
        $fileUtils->extractExtension(Argument::type('string'))->will(function ($arg) {
            return $arg[0];
        });

        $this->beConstructedWith($fileUtils);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MimeGuesser::class);
    }

    public function it_guesses_mime_by_filename()
    {
        $this->guessMimeByFilename('mkv')->shouldReturn(MimeGuesser::MIME_VIDEO);
        $this->guessMimeByFilename('mp3')->shouldReturn(MimeGuesser::MIME_AUDIO);
        $this->guessMimeByFilename('png')->shouldReturn(MimeGuesser::MIME_IMAGE);
        $this->guessMimeByFilename('zip')->shouldReturn(MimeGuesser::MIME_ARCHIVE);
        $this->guessMimeByFilename('apk')->shouldReturn(MimeGuesser::MIME_EXECUTABLE);
        $this->guessMimeByFilename('dmg')->shouldReturn(MimeGuesser::MIME_ISO);
        $this->guessMimeByFilename('fdf')->shouldReturn(MimeGuesser::MIME_PDF);
        $this->guessMimeByFilename('xls')->shouldReturn(MimeGuesser::MIME_OTHER);
    }

    public function it_guesses_directory_if_filename_has_no_extension()
    {
        $this->guessMimeByFilename('')->shouldReturn(MimeGuesser::MIME_DIRECTORY);
    }

    public function it_guesses_mime_by_extension()
    {
        $this->guessMimeByExtension('mp4')->shouldReturn(MimeGuesser::MIME_VIDEO);
        $this->guessMimeByExtension('flac')->shouldReturn(MimeGuesser::MIME_AUDIO);
        $this->guessMimeByExtension('jpg')->shouldReturn(MimeGuesser::MIME_IMAGE);
        $this->guessMimeByExtension('rar')->shouldReturn(MimeGuesser::MIME_ARCHIVE);
        $this->guessMimeByExtension('exe')->shouldReturn(MimeGuesser::MIME_EXECUTABLE);
        $this->guessMimeByExtension('iso')->shouldReturn(MimeGuesser::MIME_ISO);
        $this->guessMimeByExtension('pdf')->shouldReturn(MimeGuesser::MIME_PDF);
        $this->guessMimeByExtension('doc')->shouldReturn(MimeGuesser::MIME_OTHER);
    }
}
