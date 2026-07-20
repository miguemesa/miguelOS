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

    public function search(string $query): array
    {
        $term = '%' . $query . '%';

        $rows = Database::fetchAll(
            "
        SELECT *
        FROM projects
        WHERE name LIKE :name_query
           OR description LIKE :description_query
        ORDER BY priority,
                 due_date
        ",
            [
                'name_query' => $term,
                'description_query' => $term,
            ]
        );

        return Hydrator::collection(
            Project::class,
            $rows
        );
    }

    public function filter(
        ?string $query = null,
        ?string $status = null,
        ?int $priority = null,
        string $sort = 'priority',
        string $direction = 'asc'
    ): array {
        $conditions = [];
        $parameters = [];

        $query = trim((string) $query);
        $status = trim((string) $status);

        if ($query !== '') {
            $conditions[] = "
            (
                name LIKE :name_query
                OR description LIKE :description_query
            )
        ";

            $term = '%' . $query . '%';

            $parameters['name_query'] = $term;
            $parameters['description_query'] = $term;
        }

        if ($status !== '') {
            $conditions[] = 'status = :status';
            $parameters['status'] = $status;
        }

        if ($priority !== null) {
            $conditions[] = 'priority = :priority';
            $parameters['priority'] = $priority;
        }

        $allowedSorts = [
            'priority' => 'priority',
            'name' => 'name',
            'due_date' => 'due_date',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        $sortColumn = $allowedSorts[$sort]
            ?? 'priority';

        $direction = strtolower($direction) === 'desc'
            ? 'DESC'
            : 'ASC';

        $sql = "
        SELECT *
        FROM projects
    ";

        if ($conditions !== []) {
            $sql .= "
            WHERE " . implode(
                    ' AND ',
                    $conditions
                );
        }

        if ($sortColumn === 'due_date') {
            $sql .= "
            ORDER BY
                due_date IS NULL,
                due_date {$direction},
                priority ASC,
                name ASC
        ";
        } elseif ($sortColumn === 'priority') {
            $sql .= "
            ORDER BY
                priority {$direction},
                due_date IS NULL,
                due_date ASC,
                name ASC
        ";
        } else {
            $sql .= "
            ORDER BY
                {$sortColumn} {$direction},
                name ASC
        ";
        }

        $rows = Database::fetchAll(
            $sql,
            $parameters
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

    public function delete(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        Database::execute(
            "
        DELETE FROM projects
        WHERE id = :id
        ",
            [
                'id' => $id,
            ]
        );

        return true;
    }
}