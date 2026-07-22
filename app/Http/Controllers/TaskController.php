<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\ProjectService;
use App\Services\TaskService;
use App\Support\TaskPresenter;
use InvalidArgumentException;

final class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $service = new TaskService(),
        private readonly ProjectService $projectService = new ProjectService()
    ) {
    }

    public function index(): Response
    {
        $tasks = $this->service->tasks();

        $captures = [];
        $plannedTasks = [];

        foreach ($tasks as $task) {
            if (TaskPresenter::isCapture($task)) {
                $captures[] = $task;

                continue;
            }

            $plannedTasks[] = $task;
        }

        // Nuevo
        $projectService = new ProjectService();

        $projects = $projectService->projects(
            status: 'active'
        );

        return $this->view(
            'tasks/index',
            [
                'title' => 'Tareas',
                'captures' => $captures,
                'projects' => $projects,
                'plannedTasks' => $plannedTasks,
            ]
        );
    }

    public function create(): Response
    {
        return $this->view(
            'tasks/form',
            [
                'title' => 'Nueva tarea',
                'task' => null,
                'projects' => $this->availableProjects(),
                'errors' => [],
                'old' => $this->emptyFormData(),
            ]
        );
    }

    public function store(Request $request): Response
    {
        $data = $this->formData($request);

        try {
            $task = $this->service->create($data);
        } catch (InvalidArgumentException $exception) {
            return $this->view(
                'tasks/form',
                [
                    'title' => 'Nueva tarea',
                    'task' => null,
                    'projects' => $this->availableProjects(),
                    'errors' => [
                        $exception->getMessage(),
                    ],
                    'old' => $data,
                ],
                422
            );
        }

        return $this->redirect(
            '/tareas/' . (int) $task->id
        );
    }

    public function show(int $id): Response
    {
        $task = $this->service->task($id);

        if ($task === null) {
            return $this->notFound();
        }

        return $this->view(
            'tasks/show',
            [
                'title' => $task->title,
                'task' => $task,
            ]
        );
    }

    public function process(
        Request $request,
        int $id
    ): Response {
        $action = trim(
            (string) $request->input('action')
        );

        match ($action) {
            'next' => $this->service->changeStatus(
                $id,
                'next',
                [
                    'project_id' => $request->input(
                        'project_id'
                    ),
                ]
            ),

            'complete' => $this->service->changeStatus(
                $id,
                'completed'
            ),

            'delete' => $this->service->delete($id),

            default => null,
        };

        return $this->redirect('/tareas');
    }

    private function formData(
        Request $request
    ): array {
        return [
            'title' => $request->input(
                'title',
                ''
            ),
            'description' => $request->input(
                'description',
                ''
            ),
            'project_id' => $request->input(
                'project_id',
                ''
            ),
            'status' => $request->input(
                'status',
                'inbox'
            ),
            'priority' => $request->input(
                'priority',
                3
            ),
            'estimated_minutes' => $request->input(
                'estimated_minutes',
                ''
            ),
            'start_date' => $request->input(
                'start_date',
                ''
            ),
            'due_date' => $request->input(
                'due_date',
                ''
            ),
            'position' => 0,
        ];
    }

    private function emptyFormData(): array
    {
        return [
            'title' => '',
            'description' => '',
            'project_id' => '',
            'status' => 'inbox',
            'priority' => 3,
            'estimated_minutes' => '',
            'start_date' => '',
            'due_date' => '',
        ];
    }

    private function availableProjects(): array
    {
        return $this->projectService->projects(
            '',
            '',
            null,
            'name',
            'asc'
        );
    }

    private function notFound(): Response
    {
        return $this->view(
            'errors/404',
            [
                'title' => 'Tarea no encontrada',
                'message' => 'La tarea solicitada no existe.',
            ],
            404
        );
    }
}