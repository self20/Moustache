<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Entity\Torrent;

// @HEYLISTEN Ajouter la fonction de remove de torrent dans l’UI (dans un second temps OK).
// @HEYLISTEN Chercher fichier localement ?
// @HEYLISTEN Command to remove old downloads

class DefaultController
{
    use TorrentGetterTrait;

    /**
     * @var ClientInterface
     */
    private $torrentClient;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var FormInterface
     */
    private $torrentMenuForm;

    /**
     * @param ClientInterface $torrentClient
     * @param EngineInterface $templateEngine
     * @param FormInterface   $torrentMenuForm
     */
    public function __construct(ClientInterface $torrentClient, EngineInterface $templateEngine, FormInterface $torrentMenuForm)
    {
        $this->torrentClient = $torrentClient;
        $this->templateEngine = $templateEngine;
        $this->torrentMenuForm = $torrentMenuForm;
    }

    /**
     * Displays main page, with all the torrents.
     *
     * @return Response
     */
    public function listAction(): Response
    {
        $torrents = $this->getAllTorrents();

        return $this->render('MoustacheBundle:Default:torrents.html.twig', ['torrents' => $torrents]);
    }

    /**
     * Displays the content of a torrent.
     *
     * @param int $id
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function torrentContentAction(int $id): Response
    {
        $torrent = $this->getSingleTorrent($id);

        return $this->render('MoustacheBundle:Default:files.html.twig', ['torrent' => $torrent]);
    }

    /**
     * @param string $template
     * @param array  $values
     *
     * @return Response
     */
    private function render(string $template, array $values): Response
    {
        $values['formTorrentMenuBig'] = $this->torrentMenuForm->setData(new Torrent())->createView();
        $values['formTorrentMenu'] = $this->torrentMenuForm->setData(new Torrent())->createView();
        $values['formTorrent'] = $this->torrentMenuForm->setData(new Torrent())->createView();

        return $this->templateEngine->renderResponse($template, $values);
    }
}
