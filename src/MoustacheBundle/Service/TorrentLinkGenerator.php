<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use StandardBundle\TorrentInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Generates unpredictable download links for torrents.
 */
class TorrentLinkGenerator implements TorrentLinkGeneratorInterface
{
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

    /**
     * {@inheritdoc}
     */
    public function generatePartialAbsoluteLink(): string
    {
        return $this->kernelRouteDirectory.self::RELATIVE_KERNEL_TO_WEB_ROUTE.$this->router->generate('moustache_torrent_direct_download');
    }

    private function generateHash(TorrentInterface $torrent): string
    {
        return hash('sha256', $this->session->getId().$torrent->getHash());
    }
}
