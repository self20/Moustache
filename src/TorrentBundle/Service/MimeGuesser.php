<?php

declare(strict_types=1);

namespace TorrentBundle\Service;

use Rico\Lib\FileUtils;

class MimeGuesser
{
    const EXTENSION_VIDEO = ['3gp', '3g2', 'avi', 'flv', 'm4v', 'mkv', 'mov', 'mp4', 'mpeg', 'mpg', 'ogg', 'ogv', 'vob', 'webm', 'wmv'];
    const EXTENSION_AUDIO = ['aac', 'aiff', 'flac', 'm4a', 'm4b', 'mmf', 'mp3', 'mpc', 'oga', 'opus', 'wav', 'wma'];
    const EXTENSION_IMAGE = ['bmp', 'bpg', 'heif', 'gif', 'ico', 'jpeg', 'jpg', 'pgm', 'png', 'ppm', 'psd', 'svg', 'tiff', 'xcf'];
    const EXTENSION_ARCHIVE = ['7z', 'a', 'ar', 'bz2', 'cpt', 'cab', 'dar', 'gz', 'lz', 'lzma', 'lzo', 'rar', 's7z', 'tar', 'z'];
    const EXTENSION_EXECUTABLE = ['app', 'apk', 'elf', 'exe', 'out'];
    const EXTENSION_ISO = ['bin', 'cdi', 'cue', 'dmg', 'img', 'iso', 'mds', 'mdx'];
    const EXTENSION_PDF = ['pdf', 'fdf', ''];

    const MIME_VIDEO = 'video';
    const MIME_AUDIO = 'audio';
    const MIME_IMAGE = 'image';
    const MIME_ARCHIVE = 'archive';
    const MIME_EXECUTABLE = 'executable';
    const MIME_ISO = 'iso';
    const MIME_PDF = 'pdf';
    const MIME_DIRECTORY = 'directory';
    const MIME_OTHER = 'other';

    /**
     * @var FileUtils
     */
    private $fileUtils;

    public function __construct(FileUtils $fileUtils)
    {
        $this->fileUtils = $fileUtils;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function guessMimeByFilename(string $filename): string
    {
        $extension = $this->fileUtils->extractExtension($filename);

        if ('' === $extension) {
            return self::MIME_DIRECTORY;
        }

        return $this->guessMimeByExtension($extension);
    }

    /**
     * @param string $extension
     *
     * @return string
     */
    public function guessMimeByExtension(string $extension): string
    {
        switch (true) {
            case in_array($extension, self::EXTENSION_VIDEO, true):
                return self::MIME_VIDEO;
            case in_array($extension, self::EXTENSION_AUDIO, true):
                return self::MIME_AUDIO;
            case in_array($extension, self::EXTENSION_IMAGE, true):
                return self::MIME_IMAGE;
            case in_array($extension, self::EXTENSION_ARCHIVE, true):
                return self::MIME_ARCHIVE;
            case in_array($extension, self::EXTENSION_ISO, true):
                return self::MIME_ISO;
            case in_array($extension, self::EXTENSION_PDF, true):
                return self::MIME_PDF;
            case in_array($extension, self::EXTENSION_EXECUTABLE, true):
                return self::MIME_EXECUTABLE;
            default:
                return self::MIME_OTHER;
        }
    }
}
