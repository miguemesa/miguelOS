<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Core\Hydrator;
use App\Models\Task;

final class TaskRepository
{
    /**
     * @return array<int, Task>
     */
    public function all(): array
    {
        $rows = Database::fetchAll(
            "
            SELECT *
            FROM tasks
            ORDER BY
                position ASC,
                priority ASC,
                due_date IS NULL,
                due_date ASC,
                id ASC
            "
        );

        return Hydrator::collection(
            Task::class,
            $rows
        );
    }

    public function find(int $id): ?Task
    {
        $row = Database::fetch(
            "
            SELECT *
            FROM tasks
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
            Task::class,
            $row
        );
    }

    /**
     * @return array<int, Task>
     */
    public function byProject(int $projectId): array
    {
        $rows = Database::fetchAll(
            "
            SELECT *
            FROM tasks
            WHERE project_id = :project_id
            ORDER BY
                position ASC,
                priority ASC,
                due_date IS NULL,
                due_date ASC,
                id ASC
            ",
            [
                'project_id' => $projectId,
            ]
        );

        return Hydrator::collection(
            Task::class,
            $rows
        );
    }

    /**
     * @return array<int, Task>
     */
    public function withoutProject(): array
    {
        $rows = Database::fetchAll(
            "
            SELECT *
            FROM tasks
            WHERE project_id IS NULL
            ORDER BY
                position ASC,
                priority ASC,
                due_date IS NULL,
                due_date ASC,
                id ASC
            "
        );

        return Hydrator::collection(
            Task::class,
            $rows
        );
    }

    public function create(Task $task): int
    {
        Database::execute(
            "
            INSERT INTO tasks (
                project_id,
                title,
                description,
                status,
                priority,
                estimated_minutes,
                start_date,
                due_date,
                completed_at,
                position
            ) VALUES (
                :project_id,
                :title,
                :description,
                :status,
                :priority,
                :estimated_minutes,
                :start_date,
                :due_date,
                :completed_at,
                :position
            )
            ",
            [
                'project_id' => $task->project_id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'priority' => $task->priority,
                'estimated_minutes' => $task->estimated_minutes,
                'start_date' => $task->start_date,
                'due_date' => $task->due_date,
                'completed_at' => $task->completed_at,
                'position' => $task->position,
            ]
        );

        return Database::lastInsertId();
    }

    public function update(Task $task): bool
    {
        if ($task->id === null) {
            return false;
        }

        Database::execute(
            "
            UPDATE tasks
            SET project_id = :project_id,
                title = :title,
                description = :description,
                status = :status,
                priority = :priority,
                estimated_minutes = :estimated_minutes,
                start_date = :start_date,
                due_date = :due_date,
                completed_at = :completed_at,
                position = :position
            WHERE id = :id
            ",
            [
                'id' => $task->id,
                'project_id' => $task->project_id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'priority' => $task->priority,
                'estimated_minutes' => $task->estimated_minutes,
                'start_date' => $task->start_date,
                'due_date' => $task->due_date,
                'completed_at' => $task->completed_at,
                'position' => $task->position,
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
            DELETE FROM tasks
            WHERE id = :id
            ",
            [
                'id' => $id,
            ]
        );

        return true;
    }
}