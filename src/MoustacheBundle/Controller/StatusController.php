<?php

declare(strict_types=1);

namespace MoustacheBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use TorrentBundle\Client\ClientInterface;

class StatusController
{
    use TorrentGetterTrait;

    /**
     * @var ClientInterface
     */
    private $torrentClient;

    /**
     * @param ClientInterface $torrentClient
     */
    public function __construct(ClientInterface $torrentClient)
    {
        $this->torrentClient = $torrentClient;
    }

    /**
     * @return JsonResponse
     */
    public function getAllAction(): JsonResponse
    {
        return new JsonResponse($this->getAllTorrents());
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getAction(int $id): JsonResponse
    {
        return new JsonResponse($this->getSingleTorrent($id));
    }
}
