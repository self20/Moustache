<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use MoustacheBundle\Service\Redirector;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Entity\TorrentInterface;

class DownloadController
{
    use TorrentGetterTrait;

    /**
     * @var ClientInterface
     */
    private $torrentClient;

    /**
     * @var Redirector
     */
    private $redirector;

    /**
     * @param ClientInterface $torrentClient
     * @param Redirector      $redirector
     */
    public function __construct(ClientInterface $torrentClient, Redirector $redirector)
    {
        $this->torrentClient = $torrentClient;
        $this->redirector = $redirector;
    }

    /**
     * @return JsonResponse
     */
    public function downloadAction(int $id): string
    {
        $torrent = $this->getSingleTorrent($id);
        $this->isDownloadable($torrent);
    }

    private function isDownloadable(TorrentInterface $torrent): bool
    {
        if (!$torrent->isFile()) {
            throw new BadRequestHttpException('This torrent cannot be downloaded because it‘s a directory.');
        }
    }
}
