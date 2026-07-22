<?php

declare(strict_types=1);

namespace App\Models;

final class Event
{
    public ?int $id = null;

    public string $entity_type = '';

    public int $entity_id = 0;

    public string $event_type = '';

    public ?string $from_value = null;

    public ?string $to_value = null;

    public ?string $note = null;

    public array $metadata = [];

    public string $occurred_at = '';

    public string $created_at = '';
}