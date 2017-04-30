<?php

declare(strict_types=1);

namespace TorrentBundle\DataFixtures\Data;

use TorrentBundle\Entity\User;

class UserData
{
    public static $users = [];

    public static function createAll(): bool
    {
        if (!empty(self::$users)) {
            return false;
        }

        $user = new User();
        $user->setId(1);
        $user->setUsername('normal');
        $user->setEmail('normal');
        $user->setPlainPassword('test');
        $user->setEnabled(true);
        self::$users['normal'] = $user;

        $userDisable = new User();
        $userDisable->setId(2);
        $userDisable->setUsername('disable');
        $userDisable->setEmail('disable');
        $userDisable->setPlainPassword('test');
        $userDisable->setEnabled(false);
        self::$users['disable'] = $userDisable;

        $userAdmin = new User();
        $userAdmin->setId(3);
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin');
        $userAdmin->setPlainPassword('test');
        $userAdmin->addRole('ROLE_ADMIN');
        $userAdmin->setEnabled(true);
        self::$users['admin'] = $userAdmin;

        $guest = new User();
        $guest->setId(4);
        $guest->setUsername('guest');
        $guest->setEmail('guest');
        $guest->setPlainPassword('test');
        $guest->setEnabled(false);
        $guest->setConfirmationToken('01234567abcdef');
        self::$users['guest'] = $guest;

        return true;
    }

    public static function freeAll()
    {
        self::$users = [];
    }
}
