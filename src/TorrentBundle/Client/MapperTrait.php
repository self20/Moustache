<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Exception\Torrent\CannotFillTorrentException;
use TorrentBundle\Mapper\TorrentMapperInterface;

trait MapperTrait
{
    /**
     * @var TorrentMapperInterface
     */
    private $torrentMapper;

    private function doMapTorrent(TorrentInterface $torrent, $externalTorrent): TorrentInterface
    {
        try {
            $partialTorrent = $this->torrentMapper->map($torrent, $externalTorrent);

            return $this->torrentMapper->mapFiles($partialTorrent, $externalTorrent);
        } catch (\Exception $ex) {
            throw new CannotFillTorrentException(sprintf('The torrent with id “%s” cannot be filled with data.', $torrent->getHash()), 0, $ex);
        }
    }
}
