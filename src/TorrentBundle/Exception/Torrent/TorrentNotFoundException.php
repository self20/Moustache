<?php

declare(strict_types=1);

namespace TorrentBundle\Exception\Torrent;

class TorrentNotFoundException extends TorrentException
{
    const MESSAGE = 'A torrent with id “%s” was requested but it does not exist.';

    /**
     * @var int
     */
    private $id;

    /**
     * @param int        $id
     * @param int        $code
     * @param \Throwable $previous
     */
    public function __construct(int $id, int $code = 0, \Throwable $previous = null)
    {
        $this->id = $id;

        parent::__construct(sprintf(self::MESSAGE, $this->id), $code, $previous);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
