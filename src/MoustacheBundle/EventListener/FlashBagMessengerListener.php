<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use MoustacheBundle\Helper\FlashBagHelper;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

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
     * @param FilterResponseEvent $event
     *
     * @return FilterResponseEvent
     */
    public function onKernelResponse(FilterResponseEvent $event): FilterResponseEvent
    {
        $this->flashBagHelper->greetUser();

        return $event;
    }
}
