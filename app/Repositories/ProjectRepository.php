<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Core\Hydrator;
use App\Models\Project;

final class ProjectRepository
{
    public function all(): array
    {
        $rows = Database::fetchAll(
            "
            SELECT *
            FROM projects
            ORDER BY priority,
                     due_date
            "
        );

        return Hydrator::collection(
            Project::class,
            $rows
        );
    }

    public function create(Project $project): int
    {
        Database::execute(
            "
            INSERT INTO projects (
                name,
                slug,
                description,
                status,
                priority
            ) VALUES (
                :name,
                :slug,
                :description,
                :status,
                :priority
            )
            ",
            [
                'name' => $project->name,
                'slug' => $project->slug,
                'description' => $project->description,
                'status' => $project->status,
                'priority' => $project->priority,
            ]
        );

        return Database::lastInsertId();
    }

    public function slugExists(string $slug): bool
    {
        $row = Database::fetch(
            "
            SELECT id
            FROM projects
            WHERE slug = :slug
            LIMIT 1
            ",
            [
                'slug' => $slug,
            ]
        );

        return $row !== null;
    }
}