<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\DataFixtures\ORM;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TorrentBundle\DataFixtures\ORM\TorrentDataLoader;
use TorrentBundle\Entity\TorrentInterface;
use TorrentBundle\Entity\User;
use TorrentBundle\Entity\UserInterface;

class TorrentDataLoaderSpec extends ObjectBehavior
{
    public function let(EntityManagerDecorator $manager, UserInterface $user)
    {
        $manager->getReference(User::class, Argument::any())->willReturn($user);
        $manager->persist(Argument::type(TorrentInterface::class))->willReturn(null);
        $manager->flush()->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TorrentDataLoader::class);
    }

    public function it_loads_fixtures($manager)
    {
        $this->load($manager);
    }

    public function it_returns_order()
    {
        $this->getOrder()->shouldReturn(2);
    }
}
