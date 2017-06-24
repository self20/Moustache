<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use TorrentBundle\Entity\TorrentInterface;

/**
 * Generates unpredictable download links for torrents.
 */
class TorrentLinkGenerator implements TorrentLinkGeneratorInterface
{
    const RELATIVE_KERNEL_TO_WEB_ROUTE = '/../web';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $kernelRouteDirectory;

    /**
     * @param SessionInterface $session
     * @param RouterInterface  $router
     * @param string           $kernelRouteDirectory
     */
    public function __construct(SessionInterface $session, RouterInterface $router, string $kernelRouteDirectory)
    {
        $this->session = $session;
        $this->router = $router;
        $this->kernelRouteDirectory = $kernelRouteDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function generateWebLink(TorrentInterface $torrent): string
    {
        return $this->router->generate('moustache_torrent_direct_download', ['hash' => $this->generateHash($torrent), 'filename' => $torrent->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function generateAbsoluteLink(TorrentInterface $torrent): string
    {
        return urldecode($this->kernelRouteDirectory.self::RELATIVE_KERNEL_TO_WEB_ROUTE.$this->generateWebLink($torrent));
    }

    private function generateHash(TorrentInterface $torrent): string
    {
        return hash('sha256', $this->session->getId().$torrent->getHash());
    }
}
