<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use StandardBundle\TorrentInterface as StandardTorrentInterface;

interface TorrentInterface extends StandardTorrentInterface
{
    /**
     * @return UserInterface|null
     */
    public function getUser();

    /**
     * @return int|null
     */
    public function getUserId();

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user);
}
