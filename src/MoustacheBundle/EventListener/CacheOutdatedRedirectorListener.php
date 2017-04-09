<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use MoustacheBundle\Service\RedirectorInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use TorrentBundle\Exception\Client\CacheOutdatedException;

/**
 * Redirects (refresh) the user to the main page after a cache outdated exception.
 */
final class CacheOutdatedRedirectorListener
{
    /**
     * @var RedirectorInterface
     */
    private $redirector;

    /**
     * @param RedirectorInterface $redirector
     */
    public function __construct(RedirectorInterface $redirector)
    {
        $this->redirector = $redirector;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @return GetResponseForExceptionEvent|null
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof CacheOutdatedException) {
            return;
        }

        $event->setResponse($this->redirector->redirect('moustache_torrent'));

        return $event;
    }
}
