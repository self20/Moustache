<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use MoustacheBundle\Message\Handler\MessageHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Redirector implements RedirectorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var MessageHandlerInterface
     */
    private $messageHandler;

    /**
     * @param RouterInterface         $router
     * @param MessageHandlerInterface $messageHandler
     */
    public function __construct(RouterInterface $router, MessageHandlerInterface $messageHandler)
    {
        $this->router = $router;
        $this->messageHandler = $messageHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function addErrorMessage(string $message, ...$parameters)
    {
        $this->messageHandler->error($message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function addInfoMessage(string $message, ...$parameters)
    {
        $this->messageHandler->info($message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function addSuccessMessage(string $message, ...$parameters)
    {
        $this->messageHandler->success($message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function addWarnMessage(string $message, ...$parameters)
    {
        $this->messageHandler->warn($message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function redirect(string $route, array $parameters = []): Response
    {
        $completeRoute = $this->router->generate($route, $parameters);

        return new RedirectResponse($completeRoute);
    }

    /**
     * {@inheritdoc}
     */
    public function redirectToPath(string $path): Response
    {
        $this->router->match($path);

        return new RedirectResponse($path);
    }
}
