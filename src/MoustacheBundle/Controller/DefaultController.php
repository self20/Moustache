<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Entity\Torrent;

// @HEYLISTEN Upload a torrent with an URL too
// @HEYLISTEN Ajouter la fonction de remove de torrent dans l’UI (dans un second temps OK).
// @HEYLISTEN Chercher fichier localement ?
// @HEYLISTEN Classer les exceptions en configuration ou BugError

// @HEYLISTEN Solution: pour le download, faire un lien symbolique dans web (ex: /download/{token}/filename.mp3), avec un token unique, généré par fichier;
// Les liens sont supprimés au login/logout -> minimum (mais avec fileatime() aussi). Au mieux, utiliser fileatime() dans une commande cron; Après 2 minutes depuis le dernier accès, supprimer le lien symbolique.
// Expliquer dans la doc le besoin d’ajouter le cron.
// @HEYLISTEN Sécurité => Faire très attention concernant le download de fichier torrent: faire une liste blanche de chose downloadable => dossier/zip, mkv, etc. pour ne pas que des fichiers PHP, py ou autre deviennent dispo et exécutables…
// @HEYLISTEN Error images optimisations

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
     * @return string
     */
    public function listAction()
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
     * @return string
     */
    public function torrentContentAction(int $id)
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
