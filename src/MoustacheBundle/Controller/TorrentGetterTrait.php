<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Exception\TorrentAdapterException;

trait TorrentGetterTrait
{
    /**
     * @param int $id
     *
     * @throws NotFoundHttpException
     *
     * @return TorrentInterface
     */
    private function getSingleTorrent(int $id): TorrentInterface
    {
        try {
            return $this->torrentClient->get($id);
        } catch (TorrentAdapterException $ex) {
            throw new NotFoundHttpException('The requested torrent was not found.', $ex, $ex->getCode());
        }
    }

    /**
     * @return TorrentInterface[]
     */
    private function getAllTorrents(): array
    {
        return $this->torrentClient->getAll();
    }
}
