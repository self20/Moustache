<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use MoustacheBundle\Message\CanDispatchMessage;
use Symfony\Component\HttpFoundation\RequestStack;
use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Event\TorrentMissingEvent;

/**
 * Displays an error when a torrent is present in database but missing in external client.
 */
final class MissingTorrentWarnerListener
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var CanDispatchMessage
     */
    private $messageDispatcher;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param ClientInterface    $client
     * @param CanDispatchMessage $messageDispatcher
     * @param RequestStack       $requestStack
     */
    public function __construct(ClientInterface $client, CanDispatchMessage $messageDispatcher, RequestStack $requestStack)
    {
        $this->client = $client;
        $this->messageDispatcher = $messageDispatcher;
        $this->requestStack = $requestStack;
    }

    /**
     * @param TorrentMissingEvent $event
     *
     * @return TorrentMissingEvent|null
     */
    public function onTorrentMissing(TorrentMissingEvent $event)
    {
        if (!$this->shouldDispatchMessage()) {
            return;
        }

        $this->messageDispatcher->error(CanDispatchMessage::TORRENT_IS_MISSING, $event->getHash(), $this->client->getName(), $this->client->getName());

        return $event;
    }

    private function shouldDispatchMessage(): bool
    {
        return
            !$this->requestStack->getCurrentRequest()->isXmlHttpRequest()
        ;
    }
}
