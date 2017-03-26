<?php

declare(strict_types=1);

namespace TorrentBundle\Mapper;

use Rico\Lib\StringUtils;
use Rico\Lib\UrlUtils;
use TorrentBundle\Entity\FileInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Service\MimeGuesser;

class TransmissionFileMapper implements FileMapperInterface
{
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
        $file->setDownloadedByteSize($externalFile->getCompleted());
        $file->setTotalByteSize($externalFile->getSize());
        $file->setName($this->urlUtils->getResourceName($externalFile->getName()));
        $file->setFriendlyName($this->getFriendlyName($externalFile->getName()));
        $file->setMime($this->mimeGuesser->guessMimeByFilename($externalFile->getName()));
        $file->setStatus($torrent->getStatus());
        $file->setTorrent($torrent);

        return $file;
    }

    private function getFriendlyName(string $uglyName): string
    {
        // @HEYLISTEN This is duplicated with TransmissionTorrentMapper, change it.
        $tempName = $this->stringUtils->removeBracketContent($uglyName);
        $friendlyName = $this->stringUtils->underscoreToSpace($tempName);

        return $friendlyName;
    }
}
