<?php

declare(strict_types=1);

namespace TorrentBundle\Exception;

class TorrentAccessDeniedException extends TorrentAdapterException
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @param int        $id
     * @param string     $username
     * @param int        $code
     * @param \Throwable $previous
     */
    public function __construct(int $id, string $username, int $code = 0, \Throwable $previous = null)
    {
        $this->id = $id;
        $this->username = $username;

        parent::__construct(sprintf('A torrent with id “%s” was requested for user “%s”; but access is denied (may belong to another user).', $this->id, $this->username), $code, $previous);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
