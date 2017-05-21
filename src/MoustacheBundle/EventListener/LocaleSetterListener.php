<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Sets the local according to the request.
 */
final class LocaleSetterListener
{
    /**
     * @param GetResponseEvent $event
     *
     * @return GetResponseEvent|null
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->headers->has('accept-language')) {
            return;
        }

        $request->setLocale(\Locale::acceptFromHttp($request->headers->get('accept-language')));

        return $event;
    }
}
