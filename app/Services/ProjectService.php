<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use InvalidArgumentException;

final class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $repository = new ProjectRepository()
    ) {
    }

    public function projects(): array
    {
        return $this->repository->all();
    }

    public function project(int $id): ?Project
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->find($id);
    }

    public function create(array $data): Project
    {
        $project = new Project();

        $this->fillProject(
            $project,
            $data
        );

        $project->slug = $this->uniqueSlug(
            $project->name
        );

        $project->id = $this->repository->create(
            $project
        );

        return $project;
    }

    public function update(
        int $id,
        array $data
    ): ?Project {
        $project = $this->repository->find($id);

        if ($project === null) {
            return null;
        }

        $originalName = $project->name;

        $this->fillProject(
            $project,
            $data
        );

        if ($project->name !== $originalName) {
            $project->slug = $this->uniqueSlug(
                $project->name,
                $project->id
            );
        }

        $this->repository->update($project);

        return $project;
    }

    private function fillProject(
        Project $project,
        array $data
    ): void {
        $name = trim(
            (string) ($data['name'] ?? '')
        );

        $description = trim(
            (string) ($data['description'] ?? '')
        );

        $status = (string) (
            $data['status'] ?? 'idea'
        );

        $priority = (int) (
            $data['priority'] ?? 3
        );

        if ($name === '') {
            throw new InvalidArgumentException(
                'El nombre del proyecto es obligatorio.'
            );
        }

        if (mb_strlen($name) > 150) {
            throw new InvalidArgumentException(
                'El nombre no puede superar los 150 caracteres.'
            );
        }

        $allowedStatuses = [
            'idea',
            'active',
            'paused',
            'completed',
            'cancelled',
        ];

        if (!in_array(
            $status,
            $allowedStatuses,
            true
        )) {
            throw new InvalidArgumentException(
                'El estado seleccionado no es válido.'
            );
        }

        if ($priority < 1 || $priority > 5) {
            throw new InvalidArgumentException(
                'La prioridad debe estar entre 1 y 5.'
            );
        }

        $project->name = $name;

        $project->description = $description !== ''
            ? $description
            : null;

        $project->status = $status;
        $project->priority = $priority;
    }

    private function uniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = $this->slugify($name);
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->repository->slugExists(
            $slug,
            $ignoreId
        )) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function slugify(string $value): string
    {
        $transliterated = iconv(
            'UTF-8',
            'ASCII//TRANSLIT//IGNORE',
            $value
        );

        if (is_string($transliterated)) {
            $value = $transliterated;
        }

        $value = mb_strtolower($value);

        $value = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $value
        ) ?? '';

        $value = trim($value, '-');

        return $value !== ''
            ? $value
            : 'proyecto';
    }
}