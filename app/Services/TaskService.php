<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use DateTimeImmutable;
use InvalidArgumentException;

final class TaskService
{
    private const ALLOWED_STATUSES = [
        'inbox',
        'next',
        'in_progress',
        'waiting',
        'completed',
        'cancelled',
    ];

    public function __construct(
        private readonly TaskRepository $repository = new TaskRepository()
    ) {
    }

    /**
     * @return array<int, Task>
     */
    public function tasks(): array
    {
        return $this->repository->all();
    }

    public function task(int $id): ?Task
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->find($id);
    }

    /**
     * @return array<int, Task>
     */
    public function tasksByProject(
        int $projectId
    ): array {
        if ($projectId <= 0) {
            return [];
        }

        return $this->repository->byProject(
            $projectId
        );
    }

    /**
     * @return array<int, Task>
     */
    public function tasksWithoutProject(): array
    {
        return $this->repository->withoutProject();
    }

    public function create(array $data): Task
    {
        $task = new Task();

        $this->fillTask(
            $task,
            $data
        );

        $this->synchronizeCompletion($task);

        $task->id = $this->repository->create(
            $task
        );

        return $task;
    }

    public function update(
        int $id,
        array $data
    ): ?Task {
        if ($id <= 0) {
            return null;
        }

        $task = $this->repository->find($id);

        if ($task === null) {
            return null;
        }

        $this->fillTask(
            $task,
            $data
        );

        $this->synchronizeCompletion($task);

        $this->repository->update($task);

        return $task;
    }

    public function changeStatus(
        int $id,
        string $status,
        array $changes = []
    ): ?Task {
        if ($id <= 0) {
            return null;
        }

        if (!in_array(
            $status,
            self::ALLOWED_STATUSES,
            true
        )) {
            throw new InvalidArgumentException(
                'El estado de la tarea no es válido.'
            );
        }

        $task = $this->repository->find(
            $id
        );

        if ($task === null) {
            return null;
        }

        $task->status = $status;

        if (array_key_exists(
            'project_id',
            $changes
        )) {
            $task->project_id =
                $this->normalizeNullablePositiveInteger(
                    $changes['project_id']
                );
        }

        if (array_key_exists(
            'priority',
            $changes
        )) {
            $priority = (int) $changes['priority'];

            if (
                $priority < 1
                || $priority > 5
            ) {
                throw new InvalidArgumentException(
                    'La prioridad debe estar entre 1 y 5.'
                );
            }

            $task->priority = $priority;
        }

        if (array_key_exists(
            'estimated_minutes',
            $changes
        )) {
            $task->estimated_minutes =
                $this->normalizeNullablePositiveInteger(
                    $changes['estimated_minutes']
                );
        }

        $this->synchronizeCompletion(
            $task
        );

        $this->repository->update(
            $task
        );

        return $task;
    }

    public function delete(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $task = $this->repository->find($id);

        if ($task === null) {
            return false;
        }

        return $this->repository->delete($id);
    }

    private function fillTask(
        Task $task,
        array $data
    ): void {
        $title = $this->normalizeSingleLineText(
            $data['title'] ?? ''
        );

        $description = $this->normalizeDescription(
            $data['description'] ?? null
        );

        $status = strtolower(
            trim(
                (string) (
                    $data['status']
                    ?? 'inbox'
                )
            )
        );

        $priority = (int) (
            $data['priority'] ?? 3
        );

        $projectId = $this->normalizeNullablePositiveInteger(
            $data['project_id'] ?? null
        );

        $estimatedMinutes =
            $this->normalizeNullablePositiveInteger(
                $data['estimated_minutes'] ?? null
            );

        $position = (int) (
            $data['position'] ?? 0
        );

        $startDate = $this->normalizeDate(
            $data['start_date'] ?? null,
            'La fecha de inicio no es válida.'
        );

        $dueDate = $this->normalizeDate(
            $data['due_date'] ?? null,
            'La fecha límite no es válida.'
        );

        if ($title === '') {
            throw new InvalidArgumentException(
                'El título de la tarea es obligatorio.'
            );
        }

        if (mb_strlen($title) > 220) {
            throw new InvalidArgumentException(
                'El título de la tarea no puede superar los 220 caracteres.'
            );
        }

        if (!in_array(
            $status,
            self::ALLOWED_STATUSES,
            true
        )) {
            throw new InvalidArgumentException(
                'El estado de la tarea no es válido.'
            );
        }

        if ($priority < 1 || $priority > 5) {
            throw new InvalidArgumentException(
                'La prioridad debe estar entre 1 y 5.'
            );
        }

        if (
            $startDate !== null
            && $dueDate !== null
            && $dueDate < $startDate
        ) {
            throw new InvalidArgumentException(
                'La fecha límite no puede ser anterior a la fecha de inicio.'
            );
        }

        if ($position < 0) {
            $position = 0;
        }

        $task->title = $title;
        $task->description = $description;
        $task->status = $status;
        $task->priority = $priority;
        $task->project_id = $projectId;
        $task->estimated_minutes = $estimatedMinutes;
        $task->start_date = $startDate;
        $task->due_date = $dueDate;
        $task->position = $position;
    }

    private function synchronizeCompletion(
        Task $task
    ): void {
        if ($task->status === 'completed') {
            if ($task->completed_at === null) {
                $task->completed_at = date(
                    'Y-m-d H:i:s'
                );
            }

            return;
        }

        $task->completed_at = null;
    }

    private function normalizeSingleLineText(
        mixed $value
    ): string {
        $value = trim(
            (string) $value
        );

        return preg_replace(
            '/\s+/u',
            ' ',
            $value
        ) ?? $value;
    }

    private function normalizeDescription(
        mixed $value
    ): ?string {
        $value = trim(
            (string) $value
        );

        if ($value === '') {
            return null;
        }

        return preg_replace(
            '/[ \t]+/u',
            ' ',
            $value
        ) ?? $value;
    }

    private function normalizeNullablePositiveInteger(
        mixed $value
    ): ?int {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        $integer = filter_var(
            $value,
            FILTER_VALIDATE_INT
        );

        if (
            $integer === false
            || $integer <= 0
        ) {
            throw new InvalidArgumentException(
                'Los valores numéricos opcionales deben ser mayores que cero.'
            );
        }

        return $integer;
    }

    private function normalizeDate(
        mixed $value,
        string $errorMessage
    ): ?string {
        $value = trim(
            (string) $value
        );

        if ($value === '') {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $value
        );

        $errors = DateTimeImmutable::getLastErrors();

        $hasErrors = is_array($errors)
            && (
                $errors['warning_count'] > 0
                || $errors['error_count'] > 0
            );

        if (
            $date === false
            || $hasErrors
            || $date->format('Y-m-d') !== $value
        ) {
            throw new InvalidArgumentException(
                $errorMessage
            );
        }

        return $date->format('Y-m-d');
    }
}