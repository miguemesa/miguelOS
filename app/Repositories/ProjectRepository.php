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

    public function slugExists(
        string $slug,
        ?int $ignoreId = null
    ): bool {
        $sql = "
        SELECT id
        FROM projects
        WHERE slug = :slug
    ";

        $parameters = [
            'slug' => $slug,
        ];

        if ($ignoreId !== null) {
            $sql .= "
            AND id != :ignore_id
        ";

            $parameters['ignore_id'] = $ignoreId;
        }

        $sql .= "
        LIMIT 1
    ";

        return Database::fetch(
                $sql,
                $parameters
            ) !== null;
    }

    public function find(int $id): ?Project
    {
        $row = Database::fetch(
            "
        SELECT *
        FROM projects
        WHERE id = :id
        LIMIT 1
        ",
            [
                'id' => $id,
            ]
        );

        if ($row === null) {
            return null;
        }

        return Hydrator::make(
            Project::class,
            $row
        );
    }

    public function update(Project $project): bool
    {
        if ($project->id === null) {
            return false;
        }

        Database::execute(
            "
        UPDATE projects
        SET name = :name,
            slug = :slug,
            description = :description,
            status = :status,
            priority = :priority
        WHERE id = :id
        ",
            [
                'id' => $project->id,
                'name' => $project->name,
                'slug' => $project->slug,
                'description' => $project->description,
                'status' => $project->status,
                'priority' => $project->priority,
            ]
        );

        return true;
    }
}