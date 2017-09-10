<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use MoustacheBundle\Service\Redirector;
use Symfony\Component\HttpFoundation\Response;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Exception\Permission\NotEnoughDiskSpaceException;

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
     *
     * @return Response
     */
    public function stopAction(int $id): Response
    {
        $torrent = $this->getSingleTorrent($id);

        $this->torrentClient->stop($torrent);

        $this->redirector->addSuccessMessage('“%s” has been suspended.', $torrent->getFriendlyName());

        return $this->redirector->redirect('moustache_torrent');
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function startAction(int $id): Response
    {
        $torrent = $this->getSingleTorrent($id);

        $this->doStartAction($torrent);

        return $this->redirector->redirect('moustache_torrent');
    }

    // @HEYLISTEN This is always the same thing: try catch and redirector addMessage + potentially logger. : Must be deduplicated
    private function doStartAction(TorrentInterface $torrent)
    {
        try {
            $this->torrentClient->start($torrent);

            $this->redirector->addSuccessMessage('“%s” has been started.', $torrent->getFriendlyName());
        } catch (NotEnoughDiskSpaceException $ex) {
            $this->redirector->addWarnMessage(
                'The torrent cannot be started because there is not enough disk space (needed: %s, available: %s).',
                $ex->getNeededSpace(),
                $ex->getAvailableSpace()
            );
        }
    }
}
