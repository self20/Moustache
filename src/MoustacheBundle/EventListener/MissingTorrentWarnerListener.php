<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use MoustacheBundle\Helper\FlashBagHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\TorrentMissingEvent;

/**
 * Displays an error when a torrent is present in database but missing in external client.
 */
final class MissingTorrentWarnerListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FlashBagHelper
     */
    private $flashBagHelper;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param LoggerInterface $logger
     * @param FlashBagHelper  $flashBagHelper
     * @param ClientInterface $client
     * @param RequestStack    $requestStack
     */
    public function __construct(LoggerInterface $logger, FlashBagHelper $flashBagHelper, ClientInterface $client, RequestStack $requestStack)
    {
        $this->logger = $logger;
        $this->flashBagHelper = $flashBagHelper;
        $this->client = $client;
        $this->requestStack = $requestStack;
    }

    /**
     * @param TorrentMissingEvent $event
     *
     * @return TorrentMissingEvent|null
     */
    public function onTorrentMissing(TorrentMissingEvent $event)
    {
        if (!$this->shouldProcess()) {
            return;
        }

        $this->flashBagHelper->warnTorrentIsMissing();

        $this->logger->error(sprintf(
            'A torrent with hash “%s” was requested but it was not found by “%s” client. It may have been removed manually in %s but still exists in moustache database or cache may has been temporarly out of date. Please fix the problem, as it can lead to performance issues.',
            $event->getHash(),
            $this->client->getName(),
            $this->client->getName()
        ));

        return $event;
    }

    private function shouldProcess(): bool
    {
        return !$this->requestStack->getCurrentRequest()->isXmlHttpRequest();
    }
}
