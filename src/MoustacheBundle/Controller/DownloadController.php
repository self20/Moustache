<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use MoustacheBundle\Exception\Permission\DownloadPermissionException;
use MoustacheBundle\Service\Redirector;
use MoustacheBundle\Service\RedirectorInterface;
use MoustacheBundle\Service\TorrentPublisher;
use Symfony\Component\HttpFoundation\Response;
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
     * @var TorrentPublisher
     */
    private $torrentPublisher;

    /**
     * @var RedirectorInterface
     */
    private $redirector;

    /**
     * @param ClientInterface     $torrentClient
     * @param TorrentPublisher    $torrentPublisher
     * @param RedirectorInterface $redirector
     */
    public function __construct(ClientInterface $torrentClient, TorrentPublisher $torrentPublisher, RedirectorInterface $redirector)
    {
        $this->torrentClient = $torrentClient;
        $this->torrentPublisher = $torrentPublisher;
        $this->redirector = $redirector;
    }

    /**
     * @param int $id
     *
     * @throws BadRequestHttpException
     * @throws DownloadPermissionException
     *
     * @return Response
     */
    public function downloadAction(int $id): Response
    {
        $downloadLink = $this->doPublishTorrent($this->getSingleTorrent($id));

        return $this->redirector->redirectToPath($downloadLink);
    }

    private function doPublishTorrent(TorrentInterface $torrent): string
    {
        try {
            return $this->torrentPublisher->publish($torrent);
        } catch (DownloadPermissionException $ex) {
            throw new BadRequestHttpException($ex->getMessage(), 0, $ex);
        }
    }
}
