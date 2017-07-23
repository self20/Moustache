<?php

declare(strict_types=1);

namespace TorrentBundle\Entity;

use StandardBundle\FileInterface as StandardFileInterface;
use StandardBundle\TorrentInterface as StandardTorrentInterface;

class File implements StandardFileInterface
{
    use EntityTrait;
    use CanBeIncompleteTrait;
    use CanBeBrowsedTrait;

    /**
     * @var StandardTorrentInterface
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
     *     min=0, minMessage="Torrent file current size is invalid (given: “{{ value }}”).",
     *     invalidMessage="Torrent file current size must be an integer."
     * )
     */
    protected $currentByteSize = 0;

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
    public function getTorrent(): StandardTorrentInterface
    {
        return $this->torrent;
    }

    /**
     * {@inheritdoc}
     */
    public function setTorrent(StandardTorrentInterface $torrent)
    {
        $this->torrent = $torrent;
    }
}
