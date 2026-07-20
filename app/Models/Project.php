<?php

declare(strict_types=1);

namespace App\Models;

final class Project
{
    public ?int $id = null;

    public string $name = '';

    public string $slug = '';

    public ?string $description = null;

    public string $status = 'idea';

    public int $priority = 3;

    public ?string $start_date = null;

    public ?string $due_date = null;

    public ?string $completed_at = null;

    public ?string $created_at = null;

    public ?string $updated_at = null;
}