<?php

declare(strict_types=1);

namespace TorrentBundle\Mapper;

use StandardBundle\FileInterface;
use StandardBundle\TorrentInterface;

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
