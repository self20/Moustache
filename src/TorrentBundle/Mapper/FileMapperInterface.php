<?php

namespace TorrentBundle\Mapper;

use TorrentBundle\Entity\FileInterface;
use TorrentBundle\Entity\TorrentInterface;

interface FileMapperInterface
{
    /**
     * @param FileInterface    $file
     * @param mixed            $externalFile
     * @param TorrentInterface $torrent
     *
     * @return FileInterface
     */
    public function map(FileInterface $file, $externalFile, TorrentInterface $torrent): FileInterface;
}
