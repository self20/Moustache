<?php

declare(strict_types=1);

namespace TorrentBundle\Exception;

class TorrentNotFoundException extends TorrentAdapterException
{
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

        parent::__construct(sprintf('A torrent with id “%s” was requested but it does not exist.', $this->id), $code, $previous);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
