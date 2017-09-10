<?php

declare(strict_types=1);

namespace MoustacheBundle\EventListener;

use MoustacheBundle\Message\CanDispatchMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Manager\UserManager;

//@HEYLISTEN Rename this class to somehting like UserGreeterListener
class UserGreeterListener
{
    const HOME_ROUTE = 'moustache_torrent';

    /**
     * @var CanDispatchMessage
     */
    private $messageDispatcher;

    /**
     * @var AuthenticatedUserHelper
     */
    private $authenticatedUserHelper;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param CanDispatchMessage      $messageDispatcher
     * @param AuthenticatedUserHelper $authenticatedUserHelper
     * @param UserManager             $userManager
     */
    public function __construct(CanDispatchMessage $messageDispatcher, AuthenticatedUserHelper $authenticatedUserHelper, UserManager $userManager)
    {
        $this->messageDispatcher = $messageDispatcher;
        $this->authenticatedUserHelper = $authenticatedUserHelper;
        $this->userManager = $userManager;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return GetResponseEvent
     */
    public function onKernelRequest(GetResponseEvent $event): GetResponseEvent
    {
        $user = $this->authenticatedUserHelper->getWhenAvailable();
        if (!$this->shouldGreetUser($event->getRequest(), $user)) {
            return $event;
        }

        $this->messageDispatcher->info(CanDispatchMessage::GREET_USER);
        $this->userManager->incrementCurrentMessage($user);

        return $event;
    }

    private function shouldGreetUser(Request $request, UserInterface $user = null): bool
    {
        return
            null !== $user &&
            $user->isNew() &&
            self::HOME_ROUTE === $request->attributes->get('_route') &&
            !$request->isXmlHttpRequest()
        ;
    }
}
