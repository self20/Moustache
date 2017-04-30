<?php

declare(strict_types=1);

namespace TorrentBundle\DataFixtures\Data;

use DateTime;
use TorrentBundle\Entity\CanDownload;
use TorrentBundle\Entity\Torrent;

class TorrentData
{
    public static $torrents = [];

    public static function createAll(): bool
    {
        if (empty(UserData::$users)) {
            UserData::createAll();
        }

        if (!empty(self::$torrents)) {
            return false;
        }

        $torrent1 = new Torrent();
        $torrent1->setId(1);
        $torrent1->setHash('4ba824542f83e7289cb319a5fff082f95739a9c3');
        $torrent1->setUser(UserData::$users['normal']);
        $torrent1->setName('Rinne no Lagrange');
        $torrent1->setStartDate(new DateTime('2016-11-29 17:59:47'));
        $torrent1->setDownloadDir('/var/downloads');
        $torrent1->setDownloadRate(0);
        $torrent1->setUploadRate(0);
        $torrent1->setNbPeers(2);
        $torrent1->setTotalByteSize(30414388907);
        $torrent1->setDownloadedByteSize(30414388907);
        $torrent1->setFriendlyName('Rinne no Lagrange');
        $torrent1->setStatus(CanDownload::STATUS_DONE);
        $torrent1->setMime('directory');
        self::$torrents[1] = $torrent1;

        $torrent2 = new Torrent();
        $torrent2->setId(2);
        $torrent2->setHash('536fd3c94021d38c0259dbdda85ddb6169b14821');
        $torrent2->setUser(UserData::$users['disable']);
        $torrent2->setName('[Group]_The_great_movie_(1920x1080_Blu-ray_FLAC)_[5DEEFZD].mkv');
        $torrent2->setStartDate(new DateTime('2016-11-14 16:38:47'));
        $torrent2->setDownloadDir('/var/disable');
        $torrent2->setDownloadRate(0);
        $torrent2->setUploadRate(0);
        $torrent2->setNbPeers(0);
        $torrent2->setTotalByteSize(14312686115);
        $torrent2->setDownloadedByteSize(14312686115);
        $torrent2->setFriendlyName('The great movie (1920x1080 Blu-ray FLAC).mkv');
        $torrent2->setStatus(CanDownload::STATUS_DONE);
        $torrent2->setMime('video');
        self::$torrents[2] = $torrent2;

        $torrent3 = new Torrent();
        $torrent3->setId(3);
        $torrent3->setHash('dd0c58763f8a29a6d9d1eb465efbe08fba14ccfd');
        $torrent3->setUser(UserData::$users['normal']);
        $torrent3->setName('[Positron] Digimon Adventure 02 (Zero Two) 01-50 Complete (DVD-MKV-H264)');
        $torrent3->setStartDate(new DateTime('2015-03-19 12:39:17'));
        $torrent3->setDownloadDir('/var/downloads');
        $torrent3->setDownloadRate(0);
        $torrent3->setUploadRate(0);
        $torrent3->setNbPeers(0);
        $torrent3->setTotalByteSize(9772511564);
        $torrent3->setDownloadedByteSize(9772511564);
        $torrent3->setFriendlyName('Digimon Adventure 02 (Zero Two) 01-50 Complete (DVD-MKV-H264)');
        $torrent3->setStatus(CanDownload::STATUS_DONE);
        $torrent3->setMime('directory');
        self::$torrents[3] = $torrent3;

        $torrent4 = new Torrent();
        $torrent4->setId(4);
        $torrent4->setHash('1d704a8810f98965f30abd6fb3e7ce75ab8d1fde');
        $torrent4->setUser(UserData::$users['normal']);
        $torrent4->setName('Digimon Tamers [WildBunch][PLSP][PLSP-card]');
        $torrent4->setStartDate(new DateTime('2016-01-21 22:02:13'));
        $torrent4->setDownloadDir('/var/downloads');
        $torrent4->setDownloadRate(5424);
        $torrent4->setUploadRate(0);
        $torrent4->setNbPeers(5);
        $torrent4->setTotalByteSize(12250974847);
        $torrent4->setDownloadedByteSize(1243324);
        $torrent4->setFriendlyName('Digimon Tamers');
        $torrent4->setStatus(CanDownload::STATUS_DOWNLOADING);
        $torrent4->setMime('directory');
        self::$torrents[4] = $torrent4;

        $torrent5 = new Torrent();
        $torrent5->setId(5);
        $torrent5->setHash('c229b45c6f447d733bba873bc22c5838aec4edb9');
        $torrent5->setUser(UserData::$users['normal']);
        $torrent5->setName('Disgaea 5');
        $torrent5->setStartDate(new DateTime('2014-10-21 20:12:53'));
        $torrent5->setDownloadDir('/var/downloads');
        $torrent5->setDownloadRate(0);
        $torrent5->setUploadRate(0);
        $torrent5->setNbPeers(0);
        $torrent5->setTotalByteSize(302085952);
        $torrent5->setDownloadedByteSize(0);
        $torrent5->setFriendlyName('Disgaea 5');
        $torrent5->setStatus(CanDownload::STATUS_DOWNLOADING);
        $torrent5->setMime('directory');
        self::$torrents[5] = $torrent5;

        $torrent6 = new Torrent();
        $torrent6->setId(6);
        $torrent6->setHash('d40470ee21ffd7d2e5f6875944a0d4166150c114');
        $torrent6->setUser(UserData::$users['normal']);
        $torrent6->setName('[Group]_Suite_Precure_(1920x1080_Blu-Ray)');
        $torrent6->setStartDate(new DateTime('2014-10-21 20:12:53'));
        $torrent6->setDownloadDir('/var/downloads');
        $torrent6->setDownloadRate(112000);
        $torrent6->setUploadRate(0);
        $torrent6->setNbPeers(2);
        $torrent6->setTotalByteSize(71012907225);
        $torrent6->setDownloadedByteSize(43424392768);
        $torrent6->setFriendlyName('Suite Precure (1920x1080 Blu-Ray)');
        $torrent6->setStatus(CanDownload::STATUS_DOWNLOADING);
        $torrent6->setMime('directory');
        self::$torrents[6] = $torrent6;

        $torrent7 = new Torrent();
        $torrent7->setId(7);
        $torrent7->setHash('24fdf974b5d0496cb44aacd433fad9cf0ce7f0b0');
        $torrent7->setUser(UserData::$users['disable']);
        $torrent7->setName('[HEY]_Kiki\'s_Delivery_Service_[3897E1F1].mkv');
        $torrent7->setStartDate(new DateTime('2016-09-15 16:14:55'));
        $torrent7->setDownloadDir('/var/disable');
        $torrent7->setDownloadRate(0);
        $torrent7->setUploadRate(340);
        $torrent7->setNbPeers(1);
        $torrent7->setTotalByteSize(14676939902);
        $torrent7->setDownloadedByteSize(14676939902);
        $torrent7->setFriendlyName('Kiki\'s Delivery Service.mkv');
        $torrent7->setStatus(CanDownload::STATUS_DONE);
        $torrent7->setMime('video');
        self::$torrents[7] = $torrent7;

        $torrent8 = new Torrent();
        $torrent8->setId(8);
        $torrent8->setHash('9bdc3cb4acedd9c930d11700c82711604704ef76');
        $torrent8->setUser(UserData::$users['normal']);
        $torrent8->setName('[FUN]_THE FUNE MOVIE (Xvid).mkv');
        $torrent8->setStartDate(new DateTime('2016-09-15 18:18:55'));
        $torrent8->setDownloadDir('/var/downloads');
        $torrent8->setDownloadRate(0);
        $torrent8->setUploadRate(0);
        $torrent8->setNbPeers(0);
        $torrent8->setTotalByteSize(6883336600);
        $torrent8->setDownloadedByteSize(6883336600);
        $torrent8->setFriendlyName('THE FUNE MOVIE (Xvid).mkv');
        $torrent8->setStatus(CanDownload::STATUS_DONE);
        $torrent8->setMime('video');
        self::$torrents[8] = $torrent8;

        $torrent9 = new Torrent();
        $torrent9->setId(9);
        $torrent9->setHash('3325f65bd67de5b6d1fc7b672372557f9dc0eccb');
        $torrent9->setUser(UserData::$users['disable']);
        $torrent9->setName('Big song.mp3');
        $torrent9->setStartDate(new DateTime('2011-01-05 19:14:25'));
        $torrent9->setDownloadDir('/var/disable');
        $torrent9->setDownloadRate(0);
        $torrent9->setUploadRate(0);
        $torrent9->setNbPeers(0);
        $torrent9->setTotalByteSize(4660024961);
        $torrent9->setDownloadedByteSize(4660024961);
        $torrent9->setFriendlyName('Big song.mp3');
        $torrent9->setStatus(CanDownload::STATUS_DONE);
        $torrent9->setMime('audio');
        self::$torrents[9] = $torrent9;

        $torrent10 = new Torrent();
        $torrent10->setId(10);
        $torrent10->setHash('928bd5669618a426aacbdc0cc919bff02e324b9e');
        $torrent10->setUser(UserData::$users['normal']);
        $torrent10->setName('Les Trois Accords - Joie d\'être gai (2015) (MP3 - 320 Kbps)');
        $torrent10->setStartDate(new DateTime('2016-12-21 10:15:53'));
        $torrent10->setDownloadDir('/var/downloads');
        $torrent10->setDownloadRate(0);
        $torrent10->setUploadRate(534);
        $torrent10->setNbPeers(1);
        $torrent10->setTotalByteSize(87365169);
        $torrent10->setDownloadedByteSize(87365169);
        $torrent10->setFriendlyName('Les Trois Accords - Joie d\'être gai (2015) (MP3 - 320 Kbps)');
        $torrent10->setStatus(CanDownload::STATUS_DONE);
        $torrent10->setMime('directory');
        self::$torrents[10] = $torrent10;

        $torrent11 = new Torrent();
        $torrent11->setId(11);
        $torrent11->setHash('8ecaef61a7cb8abe22dda9bfc5748d713a875c4d');
        $torrent11->setUser(UserData::$users['normal']);
        $torrent11->setName('Les Blaireaux (2010) - Bouquet d\'Orties');
        $torrent11->setStartDate(new DateTime('2015-12-01 11:25:50'));
        $torrent11->setDownloadDir('/var/downloads');
        $torrent11->setDownloadRate(0);
        $torrent11->setUploadRate(0);
        $torrent11->setNbPeers(0);
        $torrent11->setTotalByteSize(124541774);
        $torrent11->setDownloadedByteSize(124541774);
        $torrent11->setFriendlyName('Les Blaireaux (2010) - Bouquet d\'Orties');
        $torrent11->setStatus(CanDownload::STATUS_STOP);
        $torrent11->setMime('directory');
        self::$torrents[11] = $torrent11;

        $torrent12 = new Torrent();
        $torrent12->setId(12);
        $torrent12->setHash('f1ee8aa34928ad63d80413f9a013194bbce44a43');
        $torrent12->setUser(UserData::$users['normal']);
        $torrent12->setName('Exercices pour le cours de physique de Feynman Dunod.pdf');
        $torrent12->setStartDate(new DateTime('2016-05-05 05:45:41'));
        $torrent12->setDownloadDir('/var/downloads');
        $torrent12->setDownloadRate(0);
        $torrent12->setUploadRate(0);
        $torrent12->setNbPeers(0);
        $torrent12->setTotalByteSize(2556100);
        $torrent12->setDownloadedByteSize(2556100);
        $torrent12->setFriendlyName('Exercices pour le cours de physique de Feynman Dunod.pdf');
        $torrent12->setStatus(CanDownload::STATUS_DONE);
        $torrent12->setMime('pdf');
        self::$torrents[12] = $torrent12;

        return true;
    }

    public static function freeAll()
    {
        UserData::freeAll();

        self::$torrents = [];
    }
}
