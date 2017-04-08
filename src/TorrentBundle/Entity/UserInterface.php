<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use Doctrine\ORM\PersistentCollection;
use FOS\UserBundle\Model\UserInterface as FOSUserInterface;

interface UserInterface extends EntityInterface, FOSUserInterface
{
    /**
     * @return bool
     */
    public function isNew(): bool;

    /**
     * @return int
     */
    public function getCurrentMessage(): int;

    /**
     * @return TorrentInterface[]
     */
    public function getTorrents(): PersistentCollection;

    /**
     * @param int $currentMessage
     */
    public function setCurrentMessage(int $currentMessage);
}
