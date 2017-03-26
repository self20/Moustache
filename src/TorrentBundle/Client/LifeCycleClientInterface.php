<?php

declare(strict_types=1);

namespace TorrentBundle\Client;

interface LifeCycleClientInterface extends CanStartTorrent, CanStopTorrent
{
}
