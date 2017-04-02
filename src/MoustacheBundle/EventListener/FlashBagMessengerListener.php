<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use MoustacheBundle\Helper\FlashBagHelper;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

// @HEYLISTEN Rename this to something like “GlobalMessengerListener” and class comment
class FlashBagMessengerListener
{
    /**
     * @var FlashBagHelper
     */
    private $flashBagHelper;

    /**
     * @param FlashBagHelper $flashBagHelper
     */
    public function __construct(FlashBagHelper $flashBagHelper)
    {
        $this->flashBagHelper = $flashBagHelper;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return GetResponseEvent
     */
    public function onKernelRequest(GetResponseEvent $event): GetResponseEvent
    {
        if ('moustache_torrent' === $event->getRequest()->attributes->get('_route')) {
            $this->flashBagHelper->greetUser();
        }

        return $event;
    }
}
