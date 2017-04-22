<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// HEYLISTEN Do not use annotation as it break things: can’t use trait easily, must be duplicated to add assertions…

/**
 * @ORM\Table(
 *      name="m_torrent",
 *      options={"comment"="Holds torrents"},
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="UNIQ_hash", columns={"hash"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="TorrentBundle\Repository\TorrentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Torrent implements TorrentInterface, \JsonSerializable
{
    use EntityTrait;
    use CanDownloadTrait;
    use CanUploadTrait;
    use CanBeBrowsedTrait;
    use CanBeUploadedTrait;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="hash", type="string", nullable=false, options={"comment"="Torrent hash"})
     * @Assert\NotBlank(message="One of the torrent has no hash.")
     * @Assert\Length(
     *      min = 10,
     *      max = 80,
     *      minMessage = "The torrent hash seems to be too short (min: {{ limit }}).",
     *      maxMessage = "The torrent hash seems to be too long (max: {{ limit }})."
     * )
     */
    protected $hash;

    /**
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="User", inversedBy="torrents", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var int
     * @Assert\Type(type="integer", message="The torrent user is invalid (given: “{{ value }}”).")
     * @Assert\GreaterThan(value=0, message="The torrent user is invalid (given: “{{ value }}”).")
     * @ORM\Column(name="user_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    protected $userId;

    /**
     * @var string
     * @Assert\NotBlank(message="One of the torrent has no name.")
     */
    protected $name = '';

    /**
     * @var FileInterface[]
     */
    protected $files = [];

    /**
     * @var \DateTime
     * @Assert\Date(message="The torrent start date is not a valid date.")
     */
    protected $startDate;

    /**
     * @var string
     * @Assert\NotBlank(message="The torrent download directory path seems to be empty.")
     */
    protected $downloadDir;

    /**
     * @var int
     * @Assert\Range(
     *     min=0, minMessage="Torrent download rate is invalid (given: “{{ value }}”).",
     *     invalidMessage="Torrent download rate must be an integer."
     * )
     */
    protected $downloadRate = 0;

    /**
     * @var int
     * @Assert\Range(
     *     min=0, minMessage="Torrent upload rate is invalid (given: “{{ value }}”).",
     *     invalidMessage="Torrent upload rate must be an integer."
     * )
     */
    protected $uploadRate = 0;

    /**
     * @var int
     * @Assert\Range(
     *     min=0, minMessage="Torrent number of peer is invalid (given: “{{ value }}”).",
     *     invalidMessage="number of peer must be an integer."
     * )
     */
    protected $nbPeers = 0;

    /**
     * @var int
     * @Assert\Range(
     *     min=0, minMessage="Torrent total size is invalid (given: “{{ value }}”).",
     *     invalidMessage="Torrent total size must be an integer."
     * )
     */
    protected $totalByteSize = 0;

    /**
     * @var int
     * @Assert\Range(
     *     min=0, minMessage="Torrent downloaded size is invalid (given: “{{ value }}”).",
     *     invalidMessage="Torrent downloaded size must be an integer."
     * )
     */
    protected $downloadedByteSize = 0;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }


    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'hash'=> $this->hash,
            'name'=> $this->name,
            'user'=> $this->user->getId(),
            'status'=> $this->status,
            'downloadRate'=> $this->downloadRate,
            'downloadHumanRate'=> $this->getDownloadHumanRate(),
            'downloadedByteSize'=> $this->downloadedByteSize,
            'downloadedHumanSize'=> $this->getDownloadedHumanSize(),
            'uploadRate'=> $this->uploadRate,
            'uploadHumanRate'=> $this->getUploadHumanRate(),
            'totalByteSize'=> $this->totalByteSize,
            'totalHumanSize'=> $this->getTotalHumanSize(),
            'percentDone'=> $this->getPercentDone(),
            'isDone'=> $this->isDone(),
            'isStopped'=> $this->isStopped(),
            'isDownloading'=> $this->isDownloading(),
            'isUploading'=> $this->isUploading(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbPeers(): int
    {
        return $this->nbPeers;
    }

    /**
     * {@inheritdoc}
     */
    public function setHash(string $hash)
    {
        $this->hash = $hash;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        $this->userId = $user->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setNbPeers(int $nbPeers)
    {
        $this->nbPeers = $nbPeers;
    }
}
