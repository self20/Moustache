<?php

namespace TorrentBundle\Mapper;

use Rico\Lib\StringUtils;
use TorrentBundle\Entity\File;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Service\MimeGuesser;

class TransmissionTorrentMapper implements TorrentMapperInterface
{
    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * @var MimeGuesser
     */
    private $mimeGuesser;

    /**
     * @var FileMapperInterface
     */
    private $fileMapper;

    public function __construct(StringUtils $stringUtils, MimeGuesser $mimeGuesser, FileMapperInterface $fileMapper)
    {
        $this->stringUtils = $stringUtils;
        $this->mimeGuesser = $mimeGuesser;
        $this->fileMapper = $fileMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function map(TorrentInterface $torrent, $externalTorrent): TorrentInterface
    {
        $torrent->setHash($externalTorrent->getHash());
        $torrent->setUploadRate($externalTorrent->getUploadRate());
        $torrent->setDownloadRate($externalTorrent->getDownloadRate());
        $torrent->setStatus($externalTorrent->getStatus());
        $torrent->setStartDate($externalTorrent->getStartDate() ? new \Datetime('@'.$externalTorrent->getStartDate()) : new \DateTime());
        $torrent->setDownloadedByteSize((int) ($externalTorrent->getSize() * $externalTorrent->getPercentDone() / 100));
        $torrent->setTotalByteSize($externalTorrent->getSize());
        $torrent->setFriendlyName($this->getFriendlyName($externalTorrent->getName()));
        $torrent->setName($externalTorrent->getName());
        $torrent->setNbPeers(count($externalTorrent->getPeers()));
        $torrent->setDownloadDir($externalTorrent->getDownloadDir() ?? '');
        if (1 === count($externalTorrent->getFiles())) {
            $torrent->setMime($this->mimeGuesser->guessMimeByFilename($externalTorrent->getName()));
        } else {
            $torrent->setMime(MimeGuesser::MIME_DIRECTORY);
        }
        $torrent->setFiles([]);

        return $torrent;
    }

    /**
     * {@inheritdoc}
     */
    public function mapFiles(TorrentInterface $torrent, $externalTorrent): TorrentInterface
    {
        $files = array_map(function ($externalFile) use ($torrent) {
            return $this->fileMapper->map(new File(), $externalFile, $torrent);
        }, $externalTorrent->getFiles());

        $torrent->setFiles($files);

        return $torrent;
    }

    private function getFriendlyName(string $uglyName): string
    {
        $tempName = $this->stringUtils->removeBracketContent($uglyName);
        $friendlyName = $this->stringUtils->underscoreToSpace($tempName);

        return $friendlyName;
    }
}
