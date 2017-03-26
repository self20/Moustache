<?php

declare(strict_types=1);

namespace TorrentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TorrentBundle\DataFixtures\Data\TorrentData;
use TorrentBundle\DataFixtures\Data\UserData;
use TorrentBundle\Entity\User;

class TorrentDataLoader extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        TorrentData::createAll();

        foreach (TorrentData::$torrents as $torrent) {
            $torrent->setUser($manager->getReference(User::class, $torrent->getUser()->getId()));
            $manager->persist($torrent);
        }

        $manager->flush();

        TorrentData::freeAll();
        UserData::freeAll();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
