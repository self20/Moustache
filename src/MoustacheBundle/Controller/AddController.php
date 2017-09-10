<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use Exception;
use MoustacheBundle\Service\RedirectorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Exception\Permission\NotEnoughDiskSpaceException;
use TorrentBundle\Manager\TorrentManager;

class AddController
{
    /**
     * @var FormInterface
     */
    private $torrentMenuForm;

    /**
     * @var ClientInterface
     */
    private $torrentClient;

    /**
     * @var TorrentManager
     */
    private $torrentManager;

    /**
     * @var RedirectorInterface
     */
    private $redirector;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param FormInterface       $torrentMenuForm
     * @param ClientInterface     $torrentClient
     * @param TorrentManager      $torrentManager
     * @param RedirectorInterface $redirector
     * @param RequestStack        $requestStack
     * @param LoggerInterface     $logger
     */
    public function __construct(
        FormInterface $torrentMenuForm,
        ClientInterface $torrentClient,
        TorrentManager $torrentManager,
        RedirectorInterface $redirector,
        RequestStack $requestStack,
        LoggerInterface $logger
    ) {
        $this->torrentMenuForm = $torrentMenuForm;
        $this->torrentClient = $torrentClient;
        $this->torrentManager = $torrentManager;
        $this->redirector = $redirector;
        $this->request = $requestStack->getCurrentRequest();
        $this->logger = $logger;
    }

    /**
     * @return Response
     */
    public function addAction(): Response
    {
        // @HEYLISTEN Handle magnet links
        $this->torrentMenuForm->handleRequest($this->request);
        if ($this->torrentMenuForm->isSubmitted() && $this->torrentMenuForm->isValid()) {
            $this->addTorrent($this->torrentMenuForm->getData());
        }

        if (!$this->torrentMenuForm->isValid()) {
            $this->redirector->addErrorMessage('%s', (string) $this->torrentMenuForm->getErrors(true));
        }

        return $this->redirector->redirect('moustache_torrent');
    }

    private function addTorrent(TorrentInterface $torrent)
    {
        try {
            return $this->torrentClient->add($torrent);
        } catch (NotEnoughDiskSpaceException $ex) {
            $this->redirector->addWarnMessage(
                'The torrent has been added but it cannot be started because there is not enough disk space (needed: %s, available: %s).',
                $ex->getNeededSpace(),
                $ex->getAvailableSpace()
            );
        } catch (Exception $ex) {
            // @HEYLISTEN Logs only loggable exception.
            $this->logger->error('A user failed to add a new torrent.', ['exception' => $ex]);
            $this->redirector->addErrorMessage('Sorry, Moustache couldn’t add your torrent. The torrent manager returned an error.');
        }
    }
}
