<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * Throws a 503 error when server is in maintenance.
 */
final class MaintenanceListener
{
    /**
     * @var string
     */
    private $maintenanceLockFile;

    /**
     * @param string $maintenanceLockFile
     */
    public function __construct(string $maintenanceLockFile)
    {
        $this->maintenanceLockFile = $maintenanceLockFile;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws ServiceUnavailableHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->shouldSetMaintenance($event->getRequest())) {
            return;
        }

        $event->stopPropagation();

        throw new ServiceUnavailableHttpException();
    }

    private function shouldSetMaintenance(Request $request): bool
    {
        return
            $this->lockFileExists() &&
            !$this->requestHasException($request)
        ;
    }

    private function lockFileExists(): bool
    {
        return file_exists($this->maintenanceLockFile);
    }

    private function requestHasException(Request $request): bool
    {
        return $request->attributes->has('exception');
    }
}
