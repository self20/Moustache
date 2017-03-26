<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use Symfony\Component\HttpFoundation\Response;

interface RedirectorInterface extends CanAddMessage
{
    /**
     * @param string $route
     * @param array  $parameters
     *
     * @return ResponseInterface
     */
    public function redirect(string $route, array $parameters = []): Response;
}
