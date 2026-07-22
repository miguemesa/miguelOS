<?php

declare(strict_types=1);

namespace App\Domain;

final class EventType
{
    public const CREATED = 'created';
    public const UPDATED = 'updated';

    public const STATUS_CHANGED = 'status_changed';
    public const PRIORITY_CHANGED = 'priority_changed';

    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    public const FOLLOW_UP = 'follow_up';
    public const RESOLVED = 'resolved';

    public const NOTE_ADDED = 'note_added';

    private function __construct()
    {
    }
}