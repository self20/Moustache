<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use MoustacheBundle\Service\Redirector;
use TorrentBundle\Client\ClientInterface;

class LifeCycleController
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
     */
    public function stopAction(int $id)
    {
        $torrent = $this->getSingleTorrent($id);

        $this->torrentClient->stop($torrent);

        $this->redirector->addSuccessMessage('“%s” has been suspended.', $torrent->getFriendlyName());

        return $this->redirector->redirect('moustache_torrent');
    }

    /**
     * @param int $id
     */
    public function startAction(int $id)
    {
        $torrent = $this->getSingleTorrent($id);

        $this->torrentClient->startNow($torrent);

        $this->redirector->addSuccessMessage('“%s” has been started.', $torrent->getFriendlyName());

        return $this->redirector->redirect('moustache_torrent');
    }
}
