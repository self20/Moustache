<?php

declare(strict_types=1);

namespace TorrentBundle\Event;

class Events
{
    const AFTER_CLIENT_INITIALIZED = 'event.client.after.initialize';

    const AFTER_TORRENT_ADDED = 'event.torrent.after.added';

    const AFTER_TORRENT_GET = 'event.torrent.after.get';

    const AFTER_TORRENT_REMOVED = 'event.torrent.after.removed';

    const AFTER_TORRENT_PAUSED = 'event.torrent.after.paused';

    const AFTER_TORRENT_STARTED = 'event.torrent.after.started';

    const AFTER_TORRENT_STOPPED = 'event.torrent.after.stopped';
}
