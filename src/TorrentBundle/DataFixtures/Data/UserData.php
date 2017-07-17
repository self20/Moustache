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

        self::createOneUser([
            'id' => 1, 'enabled' => true,
            'username' => 'normal', 'password' => 'test',
        ]);

        self::createOneUser([
            'id' => 2, 'enabled' => false,
            'username' => 'disable', 'password' => 'test',
        ]);

        self::createOneUser([
            'id' => 3, 'enabled' => true, 'role' => 'ROLE_ADMIN',
            'username' => 'admin', 'password' => 'test',
        ]);

        self::createOneUser([
            'id' => 4, 'enabled' => true, 'confirmationToken' => '01234567abcdef',
            'username' => 'guest', 'password' => 'test',
        ]);

        return true;
    }

    public static function createOneUser(array $data, UserInterface $user = null)
    {
        if (null === $user) {
            $user = new User();
        }

        $user->setId($data['id']);
        $user->setUsername($data['username']);
        $user->setEmail($data['username']);
        $user->setPlainPassword($data['password']);
        $user->setEnabled($data['enabled']);
        $user->setConfirmationToken($data['confirmationToken'] ?? null);
        if (isset($data['role'])) {
            $user->addRole('role');
        }
        self::$users[$data['username']] = $user;
    }

    public static function freeAll()
    {
        self::$users = [];
    }
}
