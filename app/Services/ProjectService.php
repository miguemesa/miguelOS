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

    public function create(array $data): Project
    {
        $name = trim((string) ($data['name'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $status = (string) ($data['status'] ?? 'idea');
        $priority = (int) ($data['priority'] ?? 3);

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

        if (!in_array($status, $allowedStatuses, true)) {
            throw new InvalidArgumentException(
                'El estado seleccionado no es válido.'
            );
        }

        if ($priority < 1 || $priority > 5) {
            throw new InvalidArgumentException(
                'La prioridad debe estar entre 1 y 5.'
            );
        }

        $project = new Project();

        $project->name = $name;
        $project->slug = $this->uniqueSlug($name);
        $project->description = $description !== ''
            ? $description
            : null;
        $project->status = $status;
        $project->priority = $priority;

        $project->id = $this->repository->create($project);

        return $project;
    }

    private function uniqueSlug(string $name): string
    {
        $baseSlug = $this->slugify($name);
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->repository->slugExists($slug)) {
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