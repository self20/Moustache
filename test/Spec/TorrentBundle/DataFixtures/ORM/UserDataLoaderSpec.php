<?php

declare(strict_types=1);

namespace Spec\TorrentBundle\DataFixtures\ORM;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TorrentBundle\DataFixtures\ORM\UserDataLoader;
use TorrentBundle\Entity\UserInterface;

class UserDataLoaderSpec extends ObjectBehavior
{
    public function let(EntityManagerDecorator $manager)
    {
        $manager->persist(Argument::type(UserInterface::class))->willReturn(null);
        $manager->flush()->willReturn(null);

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserDataLoader::class);
    }

    public function it_loads_fixtures($manager)
    {
        $this->load($manager);
    }

    public function it_returns_order()
    {
        $this->getOrder()->shouldReturn(1);
    }
}
