<?php

declare(strict_types=1);

namespace TorrentBundle\DataFixtures\Data;

use DateTime;
use StandardBundle\CanDownload;
use TorrentBundle\Entity\Torrent;
use TorrentBundle\Entity\TorrentInterface;

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

        self::createOneTorrent([
            'id' => 1, 'hash' => '4ba824542f83e7289cb319a5fff082f95739a9c3', 'downloadDir' => '/var/downloads',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_DONE,
            'name' => 'Rinne no Lagrange', 'friendlyName' => 'Rinne no Lagrange', 'mime' => 'directory',
            'startDate' => new DateTime('2016-11-29 17:59:47'),
            'nbPeers' => 2, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => 30414388907, 'currentByteSize' => 30414388907,
        ]);

        self::createOneTorrent([
            'id' => 2, 'hash' => '536fd3c94021d38c0259dbdda85ddb6169b14821',
            'user' => UserData::$users['disable'], 'status' => CanDownload::STATUS_DONE, 'downloadDir' => '/var/disable',
            'name' => '[Group]_The_great_movie_(1920x1080_Blu-ray_FLAC)_[5DEEFZD].mkv', 'friendlyName' => 'The great movie (1920x1080 Blu-ray FLAC).mkv', 'mime' => 'video',
            'startDate' => new DateTime('2016-11-14 16:38:47'),
            'nbPeers' => 0, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => 14312686115, 'currentByteSize' => 14312686115,
        ]);

        self::createOneTorrent([
            'id' => 3, 'hash' => 'dd0c58763f8a29a6d9d1eb465efbe08fba14ccfd',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_DONE, 'downloadDir' => '/var/downloads',
            'name' => '[Positron] Digimon Adventure 02 (Zero Two) 01-50 Complete (DVD-MKV-H264)', 'friendlyName' => 'Digimon Adventure 02 (Zero Two) 01-50 Complete (DVD-MKV-H264)', 'mime' => 'directory',
            'startDate' => new DateTime('2015-03-19 12:39:17'),
            'nbPeers' => 0, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => 9772511564, 'currentByteSize' => 9772511564,
        ]);

        self::createOneTorrent([
            'id' => 4, 'hash' => '1d704a8810f98965f30abd6fb3e7ce75ab8d1fde',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_DOWNLOADING, 'downloadDir' => '/var/downloads',
            'name' => '[Digimon Tamers [WildBunch][PLSP][PLSP-card]', 'friendlyName' => 'Digimon Tamers', 'mime' => 'directory',
            'startDate' => new DateTime('2016-01-21 22:02:13'),
            'nbPeers' => 5, 'uploadRate' => 0, 'downloadRate' => 5424, 'totalByteSize' => 12250974847, 'currentByteSize' => 1243324,
        ]);

        self::createOneTorrent([
            'id' => 5, 'hash' => 'c229b45c6f447d733bba873bc22c5838aec4edb9',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_DOWNLOADING, 'downloadDir' => '/var/downloads',
            'name' => 'Disgaea 5', 'friendlyName' => 'Disgaea 5', 'mime' => 'directory',
            'startDate' => new DateTime('2014-10-21 20:12:53'),
            'nbPeers' => 0, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => 302085952, 'currentByteSize' => 0,
        ]);

        self::createOneTorrent([
            'id' => 6, 'hash' => 'd40470ee21ffd7d2e5f6875944a0d4166150c114',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_DOWNLOADING, 'downloadDir' => '/var/downloads',
            'name' => '[Group]_Suite_Precure_(1920x1080_Blu-Ray)', 'friendlyName' => 'Suite Precure (1920x1080 Blu-Ray)', 'mime' => 'directory',
            'startDate' => new DateTime('2014-10-21 20:12:53'),
            'nbPeers' => 2, 'uploadRate' => 0, 'downloadRate' => 112000, 'totalByteSize' => 71012907225, 'currentByteSize' => 43424392768,
        ]);

        self::createOneTorrent([
            'id' => 7, 'hash' => '24fdf974b5d0496cb44aacd433fad9cf0ce7f0b0',
            'user' => UserData::$users['disable'], 'status' => CanDownload::STATUS_DONE, 'downloadDir' => '/var/disable',
            'name' => '[HEY]_Kiki\'s_Delivery_Service_[3897E1F1].mkv', 'friendlyName' => 'Kiki\'s Delivery Service.mkv', 'mime' => 'video',
            'startDate' => new DateTime('2016-09-15 16:14:55'),
            'nbPeers' => 1, 'uploadRate' => 340, 'downloadRate' => 0, 'totalByteSize' => 14676939902, 'currentByteSize' => 14676939902,
        ]);

        self::createOneTorrent([
            'id' => 8, 'hash' => '9bdc3cb4acedd9c930d11700c82711604704ef76',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_DONE, 'downloadDir' => '/var/downloads',
            'name' => '[FUN]_THE FUNE MOVIE (Xvid).mkv', 'friendlyName' => 'THE FUNE MOVIE (Xvid).mkv', 'mime' => 'video',
            'startDate' => new DateTime('2016-09-15 18:18:55'),
            'nbPeers' => 0, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => 6883336600, 'currentByteSize' => 6883336600,
        ]);

        self::createOneTorrent([
            'id' => 9, 'hash' => '3325f65bd67de5b6d1fc7b672372557f9dc0eccb',
            'user' => UserData::$users['disable'], 'status' => CanDownload::STATUS_DONE, 'downloadDir' => '/var/disable',
            'name' => 'Big song.mp3', 'friendlyName' => 'Big song.mp3', 'mime' => 'audio',
            'startDate' => new DateTime('2011-01-05 19:14:25'),
            'nbPeers' => 0, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => 4660024961, 'currentByteSize' => 4660024961,
        ]);

        self::createOneTorrent([
            'id' => 10, 'hash' => '928bd5669618a426aacbdc0cc919bff02e324b9e',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_DONE, 'downloadDir' => '/var/downloads',
            'name' => 'Les Trois Accords - Joie d\'être gai (2015) (MP3 - 320 Kbps)', 'friendlyName' => 'Les Trois Accords - Joie d\'être gai (2015) (MP3 - 320 Kbps)', 'mime' => 'directory',
            'startDate' => new DateTime('2016-12-21 10:15:53'),
            'nbPeers' => 1, 'uploadRate' => 534, 'downloadRate' => 0, 'totalByteSize' => 87365169, 'currentByteSize' => 87365169,
        ]);

        self::createOneTorrent([
            'id' => 11, 'hash' => '8ecaef61a7cb8abe22dda9bfc5748d713a875c4d',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_STOP, 'downloadDir' => '/var/downloads',
            'name' => 'Les Blaireaux (2010) - Bouquet d\'Orties', 'friendlyName' => 'Les Blaireaux (2010) - Bouquet d\'Orties', 'mime' => 'directory',
            'startDate' => new DateTime('2015-12-01 11:25:50'),
            'nbPeers' => 0, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => 124541774, 'currentByteSize' => 124541774,
        ]);

        self::createOneTorrent([
            'id' => 12, 'hash' => 'f1ee8aa34928ad63d80413f9a013194bbce44a43',
            'user' => UserData::$users['normal'], 'status' => CanDownload::STATUS_DONE, 'downloadDir' => '/var/downloads',
            'name' => 'Exercices pour le cours de physique de Feynman Dunod.pdf', 'friendlyName' => 'Exercices pour le cours de physique de Feynman Dunod.pdf', 'mime' => 'pdf',
            'startDate' => new DateTime('2016-05-05 05:45:41'),
            'nbPeers' => 0, 'uploadRate' => 0, 'downloadRate' => 0, 'totalByteSize' => 2556100, 'currentByteSize' => 2556100,
        ]);

        return true;
    }

    public static function createOneTorrent(array $data, TorrentInterface $torrent = null)
    {
        if (null == $torrent) {
            $torrent = new Torrent();
        }

        $torrent->setId($data['id']);
        $torrent->setHash($data['hash']);
        $torrent->setUser($data['user']);
        $torrent->setName($data['name']);
        $torrent->setStartDate($data['startDate']);
        $torrent->setDownloadDir($data['downloadDir']);
        $torrent->setDownloadRate($data['downloadRate'] ?? 0);
        $torrent->setUploadRate($data['uploadRate'] ?? 0);
        $torrent->setNbPeers($data['nbPeers'] ?? 0);
        $torrent->setTotalByteSize($data['totalByteSize']);
        $torrent->setCurrentByteSize($data['currentByteSize']);
        $torrent->setFriendlyName($data['friendlyName']);
        $torrent->setStatus($data['status']);
        $torrent->setMime($data['mime']);
        self::$torrents[$data['id']] = $torrent;
    }

    public static function freeAll()
    {
        UserData::freeAll();

        self::$torrents = [];
    }
}
