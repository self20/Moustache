<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use MoustacheBundle\Service\FlashMessageGenerator;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class GlobalMessengerListener
{
    const HOME_ROUTE = 'moustache_torrent';

    /**
     * @var FlashMessageGenerator
     */
    private $flashMessageGenerator;

    /**
     * @param FlashMessageGenerator $flashMessageGenerator
     */
    public function __construct(FlashMessageGenerator $flashMessageGenerator)
    {
        $this->flashMessageGenerator = $flashMessageGenerator;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return GetResponseEvent
     */
    public function onKernelRequest(GetResponseEvent $event): GetResponseEvent
    {
        if (self::HOME_ROUTE === $event->getRequest()->attributes->get('_route')) {
            $this->flashMessageGenerator->greetUser();
        }

        return $event;
    }
}
