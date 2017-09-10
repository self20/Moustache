<?php

declare(strict_types=1);
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use TorrentBundle\Entity\Torrent;

class MoustacheContext extends MinkContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;
    use AuthenticationContextTrait;
    use DatabaseContextTrait;
    use DebugContextTrait;
    use RedirectionContextTrait;

    /**
     * @Given /^The maintenance lock is present$/
     */
    public function theMaintenanceLockIsPresent()
    {
        $maintenancelockFile = $this->getContainer()->getParameter('maintenance_lock_file');

        touch($maintenancelockFile);
    }

    /**
     * @Given /^The maintenance lock is absent$/
     */
    public function theMaintenanceLockIsAbsent()
    {
        $maintenancelockFile = $this->getContainer()->getParameter('maintenance_lock_file');

        if (file_exists($maintenancelockFile)) {
            unlink($maintenancelockFile);
        }
    }

    /**
     * @When /^(?:|I )add a new torrent in database$/
     */
    public function iAddATorrentInDatabase()
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $userRepository = $this->getContainer()->get('torrent.repository.user');

        $torrent = new Torrent();

        $torrent->setHash('missing');
        $torrent->setUser($userRepository->findOneBy(['username' => 'normal']));
        $torrent->setName('missing');
        $torrent->setDownloadDir('/var/downloads');
        $torrent->setDownloadRate(0);
        $torrent->setUploadRate(0);
        $torrent->setNbPeers(0);
        $torrent->setTotalByteSize(128000000);
        $torrent->setCurrentByteSize(0);
        $torrent->setFriendlyName('missing');

        $entityManager->persist($torrent);
        $entityManager->flush();
    }
}
