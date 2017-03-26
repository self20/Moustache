<?php

declare(strict_types=1);

namespace TorrentBundle\Helper;

use TorrentBundle\Client\ClientInterface;
use TorrentBundle\Exception\BadTorrentClientNameException;

class TorrentClientNameHelper implements HelperGetterInterface
{
    const VALID_NAMES = [ClientInterface::TRANSMISSION, ClientInterface::FAKE];

    /**
     * @var string
     */
    private $torrentClientName;

    /**
     * @param string $torrentClientName
     */
    public function __construct(string $torrentClientName)
    {
        $this->torrentClientName = $torrentClientName;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return empty($this->torrentClientName);
    }

    /**
     * @return string
     */
    public function getWhenAvailable()
    {
        return $this->torrentClientName;
    }

    /**
     * @throws BadTorrentClientNameException
     *
     * @return string
     */
    public function get()
    {
        if (!$this->isValid()) {
            throw new BadTorrentClientNameException(sprintf(
                'Torrent RPC client name given in parameters is invalid. Available: %s', implode(', ', self::VALID_NAMES)
            ));
        }

        return $this->torrentClientName;
    }

    private function isValid(): bool
    {
        return in_array($this->torrentClientName, self::VALID_NAMES, true);
    }
}
