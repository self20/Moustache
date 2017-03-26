<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

interface TorrentInterface extends EntityInterface, CanBeBrowsed, CanDownload, CanUpload, CanBeUploaded
{
    /**
     * @return string
     */
    public function getHash(): string;

    /**
     * @return UserInterface|null
     */
    public function getUser();

    /**
     * @return int|null
     */
    public function getUserId();

    /**
     * @return FileInterface[]
     */
    public function getFiles(): array;

    /**
     * @return \DateTime|null
     */
    public function getStartDate();

    /**
     * @return int
     */
    public function getNbPeers(): int;

    /**
     * @param string $hash
     */
    public function setHash(string $hash);

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user);

    /**
     * @param FileInterface[] $files
     */
    public function setFiles(array $files);

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate(\DateTime $startDate);

    /**
     * @param int $nbPeers
     */
    public function setNbPeers(int $nbPeers);
}
