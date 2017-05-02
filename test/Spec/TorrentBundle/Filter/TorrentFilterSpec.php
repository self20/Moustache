<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\Filter;

use PhpSpec\ObjectBehavior;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Entity\UserInterface;
use TorrentBundle\Filter\TorrentFilter;
use TorrentBundle\Filter\TorrentFilterInterface;
use TorrentBundle\Helper\AuthenticatedUserHelper;
use TorrentBundle\Repository\TorrentRepository;

class TorrentFilterSpec extends ObjectBehavior
{
    public function let(
        AuthenticatedUserHelper $authenticatedUserHelper,
        TorrentRepository $torrentRepository,
    
        UserInterface $user,
        TorrentInterface $torrent1,
        TorrentInterface $torrent2
    ) {
        $user->getId()->willReturn(1);
        $authenticatedUserHelper->get()->willReturn($user);

        $torrentRepository->findAllTorrentsByUser(1)->willReturn([$torrent1, $torrent2]);
        $torrentRepository->findOneByUserAndId(1, 1)->willReturn($torrent1);

        $this->beConstructedWith($authenticatedUserHelper, $torrentRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentFilter::class);
    }

    public function it_is_a_torrent_filter()
    {
        $this->shouldHaveType(TorrentFilterInterface::class);
    }

    public function it_returns_all_torrents_belonging_to_authenticated_user($torrent1, $torrent2)
    {
        $this->getAllAuthenticatedUserTorrents()->shouldReturn([$torrent1, $torrent2]);
    }

    public function it_return_a_single_torrent_by_id($torrent1)
    {
        $this->getAuthenticatedUserTorrent(1)->shouldReturn($torrent1);
    }
}
