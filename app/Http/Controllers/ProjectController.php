<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\ProjectService;
use InvalidArgumentException;

final class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectService $service = new ProjectService()
    ) {
    }

    public function create(): Response
    {
        return $this->view(
            'projects/form',
            [
                'title' => 'Nuevo proyecto',
                'project' => null,
                'errors' => [],
                'old' => $this->emptyFormData(),
            ]
        );
    }

    public function store(Request $request): Response
    {
        $data = $this->formData($request);

        try {
            $project = $this->service->create($data);
        } catch (InvalidArgumentException $exception) {
            return $this->view(
                'projects/form',
                [
                    'title' => 'Nuevo proyecto',
                    'project' => null,
                    'errors' => [
                        $exception->getMessage(),
                    ],
                    'old' => $data,
                ],
                422
            );
        }

        return $this->redirect(
            '/proyectos/' . (int) $project->id
        );
    }

    public function show(int $id): Response
    {
        $project = $this->service->project($id);

        if ($project === null) {
            return $this->notFound();
        }

        $history = $this->service->history($id);

        return $this->view(
            'projects/show',
            [
                'title' => $project->name,
                'project' => $project,
                'history' => $history,
            ]
        );
    }

    public function edit(int $id): Response
    {
        $project = $this->service->project($id);

        if ($project === null) {
            return $this->notFound();
        }

        return $this->view(
            'projects/form',
            [
                'title' => 'Editar ' . $project->name,
                'project' => $project,
                'errors' => [],
                'old' => [
                    'name' => $project->name,
                    'description' => $project->description ?? '',
                    'status' => $project->status,
                    'priority' => $project->priority,
                    'start_date' => $project->start_date ?? '',
                    'due_date' => $project->due_date ?? '',
                ],
            ]
        );
    }

    public function update(
        Request $request,
        int $id
    ): Response {
        $project = $this->service->project($id);

        if ($project === null) {
            return $this->notFound();
        }

        $data = $this->formData($request);

        try {
            $updatedProject = $this->service->update(
                $id,
                $data
            );
        } catch (InvalidArgumentException $exception) {
            return $this->view(
                'projects/form',
                [
                    'title' => 'Editar ' . $project->name,
                    'project' => $project,
                    'errors' => [
                        $exception->getMessage(),
                    ],
                    'old' => $data,
                ],
                422
            );
        }

        if ($updatedProject === null) {
            return $this->notFound();
        }

        return $this->redirect(
            '/proyectos/' . (int) $updatedProject->id
        );
    }

    public function destroy(int $id): Response
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return $this->notFound();
        }

        return $this->redirect('/');
    }

    private function formData(Request $request): array
    {
        return [
            'name' => $request->input(
                'name',
                ''
            ),
            'description' => $request->input(
                'description',
                ''
            ),
            'status' => $request->input(
                'status',
                'idea'
            ),
            'priority' => $request->input(
                'priority',
                3
            ),
            'start_date' => $request->input(
                'start_date',
                ''
            ),
            'due_date' => $request->input(
                'due_date',
                ''
            ),
        ];
    }

    private function emptyFormData(): array
    {
        return [
            'name' => '',
            'description' => '',
            'status' => 'idea',
            'priority' => 3,
            'start_date' => '',
            'due_date' => '',
        ];
    }

    private function notFound(): Response
    {
        return $this->view(
            'errors/404',
            [
                'title' => 'Proyecto no encontrado',
                'message' => 'El proyecto solicitado no existe.',
            ],
            404
        );
    }
}