<?php

declare(strict_types=1);

namespace TorrentBundle\Mapper;

use Rico\Lib\StringUtils;
use Rico\Lib\UrlUtils;
use StandardBundle\FileInterface;
use StandardBundle\TorrentInterface;
use TorrentBundle\Service\MimeGuesser;

class TransmissionFileMapper implements FileMapperInterface
{
    use FriendlyNameTrait;

    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * @var UrlUtils
     */
    private $urlUtils;

    /**
     * @var MimeGuesser
     */
    private $mimeGuesser;

    public function __construct(StringUtils $stringUtils, UrlUtils $urlUtils, MimeGuesser $mimeGuesser)
    {
        $this->stringUtils = $stringUtils;
        $this->urlUtils = $urlUtils;
        $this->mimeGuesser = $mimeGuesser;
    }

    /**
     * {@inheritdoc}
     */
    public function map(FileInterface $file, $externalFile, TorrentInterface $torrent): FileInterface
    {
        $file->setCurrentByteSize($externalFile->getCompleted());
        $file->setTotalByteSize($externalFile->getSize());
        $file->setName($this->urlUtils->getResourceName($externalFile->getName()));
        $file->setFriendlyName($this->getFriendlyName($externalFile->getName()));
        $file->setMime($this->mimeGuesser->guessMimeByFilename($externalFile->getName()));
        $file->setTorrent($torrent);

        return $file;
    }
}
