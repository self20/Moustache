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
     * @return Response
     */
    public function redirect(string $route, array $parameters = []): Response;

    /**
     * @param string $path
     *
     * @return Response
     */
    public function redirectToPath(string $path): Response;
}
