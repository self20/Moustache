<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Manager\TorrentManager;

class TorrentManagerSpec extends ObjectBehavior
{
    public function let(
        EntityManagerInterface $entityManager,
        AuthenticatedUserHelper $authenticatedUserHelper,

        TorrentInterface $torrent,
        UserInterface $user,
        UserInterface $anotherUser
    ) {
        $torrent->getUser()->willReturn($user);

        $this->beConstructedWith($entityManager, $authenticatedUserHelper);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentManager::class);
    }

    public function it_removes_an_entity($entityManager, $torrent)
    {
        $entityManager->remove($torrent)->shouldBeCalledTimes(1);

        $this->remove($torrent);
    }

    public function it_persists_an_entity($entityManager, $torrent)
    {
        $entityManager->persist($torrent)->shouldBeCalledTimes(1);

        $this->persist($torrent);
    }

    public function it_adds_authenticated_user_to_entity_if_needed($authenticatedUserHelper, $entityManager, $torrent, $anotherUser)
    {
        $torrent->getUser()->willReturn(null);
        $authenticatedUserHelper->get()->willReturn($anotherUser);

        $torrent->setUser($anotherUser)->shouldBeCalledTimes(1);
        $entityManager->persist($torrent)->shouldBeCalledTimes(1);

        $this->persist($torrent);
    }

    public function it_flushed_the_entity_manager($entityManager)
    {
        $entityManager->flush()->shouldBeCalledTimes(1);

        $this->save();
    }
}
