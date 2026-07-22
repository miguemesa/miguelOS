<?php

declare(strict_types=1);

namespace App\Models;

final class Task
{
    public ?int $id = null;

    public ?int $project_id = null;

    public string $title = '';

    public ?string $description = null;

    public string $status = 'inbox';

    public int $priority = 3;

    public ?int $estimated_minutes = null;

    public ?string $start_date = null;

    public ?string $due_date = null;

    public ?string $completed_at = null;

    public int $position = 0;

    public ?string $created_at = null;

    public ?string $updated_at = null;
}