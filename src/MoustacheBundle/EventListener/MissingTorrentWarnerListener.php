<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use MoustacheBundle\Service\FlashMessageGenerator;
use Psr\Log\LoggerInterface;
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
     * @var FlashMessageGenerator
     */
    private $flashMessageGenerator;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param LoggerInterface       $logger
     * @param FlashMessageGenerator $flashMessageGenerator
     * @param ClientInterface       $client
     */
    public function __construct(LoggerInterface $logger, FlashMessageGenerator $flashMessageGenerator, ClientInterface $client)
    {
        $this->logger = $logger;
        $this->flashMessageGenerator = $flashMessageGenerator;
        $this->client = $client;
    }

    /**
     * @param TorrentMissingEvent $event
     *
     * @return TorrentMissingEvent|null
     */
    public function onTorrentMissing(TorrentMissingEvent $event)
    {
        $this->flashMessageGenerator->warnTorrentIsMissing();

        $this->logger->error(sprintf(
            'A torrent with hash “%s” was requested but it was not found by “%s” client. It may have been removed manually in %s but still exists in moustache database or cache may has been temporarly out of date. Please fix the problem, as it can lead to performance issues.',
            $event->getHash(),
            $this->client->getName(),
            $this->client->getName()
        ));

        return $event;
    }
}
