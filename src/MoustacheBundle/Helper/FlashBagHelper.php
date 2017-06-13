<?php

declare(strict_types=1);

namespace MoustacheBundle\Helper;

use MoustacheBundle\Service\FlashMessengerInterface;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Manager\UserManager;

/**
 * @HEYLISTEN It’s more a service than a helper, so change it.
 * Adds reusable messages to the flashbag.
 */
class FlashBagHelper
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
     * @param FlashMessengerInterface $flashMessenger
     * @param AuthenticatedUserHelper $authenticatedUserHelper
     * @param UserManager $userManager
     */
    public function __construct(FlashMessengerInterface $flashMessenger, AuthenticatedUserHelper $authenticatedUserHelper, UserManager $userManager)
    {
        $this->flashMessenger = $flashMessenger;
        $this->authenticatedUserHelper = $authenticatedUserHelper;
        $this->userManager = $userManager;
    }

    /**
     * Greets a User on their first connection.
     */
    public function greetUser()
    {
        $user = $this->authenticatedUserHelper->getWhenAvailable();

        if (null === $user || !$user->isNew()) {
            return;
        }

        $this->flashMessenger->info('Hi folk! Here you can upload your .torrent files, wait a few seconds… then download the content to your computer. Sooo easy I could wax myself.');

        $this->userManager->incrementCurrentMessage($user);
    }

    public function warnTorrentIsMissing()
    {
        $user = $this->authenticatedUserHelper->get();
        if (null === $user) {
            return;
        }

        $this->flashMessenger->warn('It seems one of your torrent have been deleted unexpectedly from the system.');
    }
}
