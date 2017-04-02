<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use MoustacheBundle\Service\RedirectorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use TorrentBundle\Client\ClientInterface;
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
     * @var string
     */
    private $torrentRpcClientName;

    /**
     * @param FormInterface       $torrentMenuForm
     * @param ClientInterface     $torrentClient
     * @param TorrentManager      $torrentManager
     * @param RedirectorInterface $redirector
     * @param Request             $request
     * @param LoggerInterface     $logger
     * @param type                $torrentRpcClientName
     */
    public function __construct(FormInterface $torrentMenuForm, ClientInterface $torrentClient, TorrentManager $torrentManager, RedirectorInterface $redirector, Request $request, LoggerInterface $logger, $torrentRpcClientName)
    {
        $this->torrentMenuForm = $torrentMenuForm;
        $this->torrentClient = $torrentClient;
        $this->torrentManager = $torrentManager;
        $this->redirector = $redirector;
        $this->request = $request;
        $this->logger = $logger;
        $this->torrentRpcClientName = $torrentRpcClientName;
    }

    /**
     * @return RedirectResponse
     */
    public function addAction()
    {
        $this->torrentMenuForm->handleRequest($this->request);
        if ($this->torrentMenuForm->isSubmitted() && $this->torrentMenuForm->isValid()) {
            // Traiter l’URL
            // Télécharger le fichier de l’URL.
            // Sécuriser tout ça! Pas plus de 100Ko & .torrent
            // Mettre le fichier dans la variable file de l’entité torrent

            // Traiter le fichier torrent

            // S’il n’est pas valide, error dans le flash messager

            // @HEYLISTEN Form error handling here

            $uploadedTorrent = $this->torrentMenuForm->getData();
            $torrent = $this->addTorrent($uploadedTorrent->getUploadedFile());
//            dump($torrent);
        }
        // Some stuff

        return $this->redirector->redirect('moustache_torrent');
    }

    private function addTorrent(\SplFileInfo $torrentFile)
    {
        try {
            return $this->torrentClient->add($torrentFile);
        } catch (\Exception $ex) {
            $this->logger->error('A user failed to add a new torrent.', ['exception' => $ex]);
            $this->redirector->addErrorMessage('Sorry, Moustache couldn’t add your torrent. The torrent manager returned an error.');
        }
    }
}
