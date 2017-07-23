<?php

declare(strict_types=1);

namespace MoustacheBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Manager\UserManager;

/**
 * Adds reusable messages to the flashbag.
 */
class FlashMessageGenerator
{
    /**
     * @var FlashMessengerInterface
     */
    private $flashMessenger;

    /**
     * @var AuthenticatedUserHelper
     */
    private $authenticatedUserHelper;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param FlashMessengerInterface $flashMessenger
     * @param AuthenticatedUserHelper $authenticatedUserHelper
     * @param UserManager             $userManager
     * @param RequestStack            $requestStack
     */
    public function __construct(FlashMessengerInterface $flashMessenger, AuthenticatedUserHelper $authenticatedUserHelper, UserManager $userManager, RequestStack $requestStack)
    {
        $this->flashMessenger = $flashMessenger;
        $this->authenticatedUserHelper = $authenticatedUserHelper;
        $this->userManager = $userManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function greetUser()
    {
        $user = $this->authenticatedUserHelper->getWhenAvailable();

        if (!$this->shouldAddMessage() || !$user->isNew()) {
            return;
        }

        $this->flashMessenger->info('Hi folk! Here you can upload your .torrent files, wait a few seconds… then download the content to your computer. Sooo easy I could wax myself.');

        $this->userManager->incrementCurrentMessage($user);
    }

    public function warnTorrentIsMissing()
    {
        if (!$this->shouldAddMessage()) {
            return;
        }

        $this->flashMessenger->warn('It seems one of your torrent have been deleted unexpectedly from the system.');
    }

    private function shouldAddMessage(): bool
    {
        return
            null !== $this->authenticatedUserHelper->getWhenAvailable() &&
            !$this->request->isXmlHttpRequest()
        ;
    }
}
