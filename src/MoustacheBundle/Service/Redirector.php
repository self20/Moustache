<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

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
     * @var FlashMessengerInterface
     */
    private $flashMessenger;

    public function __construct(RouterInterface $router, FlashMessengerInterface $flashMessenger)
    {
        $this->router = $router;
        $this->flashMessenger = $flashMessenger;
    }

    /**
     * {@inheritdoc}
     */
    public function addErrorMessage(string $message, ...$parameters)
    {
        $this->flashMessenger->error($message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function addInfoMessage(string $message, ...$parameters)
    {
        $this->flashMessenger->info($message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function addSuccessMessage(string $message, ...$parameters)
    {
        $this->flashMessenger->success($message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function addWarnMessage(string $message, ...$parameters)
    {
        $this->flashMessenger->warn($message, ...$parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function redirect(string $route, array $parameters = []): Response
    {
        $completeRoute = $this->router->generate($route, $parameters);

        return new RedirectResponse($completeRoute);
    }
}
