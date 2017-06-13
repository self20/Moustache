<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use MoustacheBundle\Service\Redirector;
use Symfony\Component\HttpFoundation\Response;
use TorrentBundle\Client\ClientInterface;

class RemoveController
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
     * @param int $id
     *
     * @return Response
     */
    public function removeAction(int $id): Response
    {
        $torrent = $this->getSingleTorrent($id);

        $this->torrentClient->removeAndDeleteLocalData($torrent);

        $this->redirector->addSuccessMessage('“%s” has been completely removed.', $torrent->getFriendlyName());

        return $this->redirector->redirect('moustache_torrent');
    }
}
