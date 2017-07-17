<?php

declare(strict_types=1);

namespace TorrentBundle\Event;

class Events
{
    const AFTER_CLIENT_RETRIEVED = 'torrent.client.after.retrieved';

    const AFTER_TORRENT_ADDED = 'torrent.torrent.after.added';

    const AFTER_TORRENT_GET = 'torrent.torrent.after.get';

    const AFTER_TORRENT_REMOVED = 'torrent.torrent.after.removed';

    const AFTER_TORRENT_PAUSED = 'torrent.torrent.after.paused';

    const AFTER_TORRENT_STARTED = 'torrent.torrent.after.started';

    const AFTER_TORRENT_STOPPED = 'torrent.torrent.after.stopped';

    const TORRENT_MISSING = 'torrent.missing';
}
