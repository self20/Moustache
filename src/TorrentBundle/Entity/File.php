<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

class File implements FileInterface
{
    use EntityTrait;
    use CanDownloadTrait;
    use CanBeBrowsedTrait;

    /**
     * @var TorrentInterface
     */
    protected $torrent;

    /**
     * @var string
     * @Assert\NotBlank(message="One of the torrent file has no name.")
     */
    protected $name = '';

    /**
     * @var string
     * @Assert\NotBlank(message="One of the torrent has no name.")
     */
    protected $trueName = '';

    /**
     * @var int
     * @Assert\Range(
     *     min=0, minMessage="Torrent file total size is invalid (given: “{{ value }}”).",
     *     invalidMessage="Torrent file total size must be an integer."
     * )
     */
    protected $totalByteSize = 0;

    /**
     * @var int
     * @Assert\Range(
     *     min=0, minMessage="Torrent file downloaded size is invalid (given: “{{ value }}”).",
     *     invalidMessage="Torrent file downloaded size must be an integer."
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

    /**
     * {@inheritdoc}
     */
    public function isDone(): bool
    {
        return $this->downloadedByteSize === $this->totalByteSize;
    }

    /**
     * {@inheritdoc}
     */
    public function getTorrent(): TorrentInterface
    {
        return $this->torrent;
    }

    /**
     * {@inheritdoc}
     */
    public function setTorrent(TorrentInterface $torrent)
    {
        $this->torrent = $torrent;
    }
}
