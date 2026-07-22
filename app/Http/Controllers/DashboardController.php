<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Config;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\ProjectService;
use App\Services\TaskService;
use App\Support\DashboardPresenter;
use InvalidArgumentException;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly ProjectService $projectService =
        new ProjectService(),
        private readonly TaskService $taskService =
        new TaskService()
    ) {
    }

    public function index(Request $request): Response
    {
        $query = trim(
            (string) $request->query('q', '')
        );

        $status = trim(
            (string) $request->query('status', '')
        );

        $priorityValue = trim(
            (string) $request->query('priority', '')
        );

        $priority = $priorityValue !== ''
            ? (int) $priorityValue
            : null;

        $sort = trim(
            (string) $request->query(
                'sort',
                'priority'
            )
        );

        $direction = trim(
            (string) $request->query(
                'direction',
                'asc'
            )
        );

        /*
         * Proyectos mostrados en el listado.
         * Sí respetan búsqueda, filtros y ordenamiento.
         */
        $projects = $this->projectService->projects(
            $query,
            $status,
            $priority,
            $sort,
            $direction
        );

        /*
         * Conjunto completo para los indicadores generales.
         * No aplicamos los filtros actuales del listado.
         */
        $allProjects = $this->projectService->projects(
            '',
            '',
            null,
            'priority',
            'asc'
        );

        $summary = DashboardPresenter::summary(
            $allProjects
        );

        return $this->view(
            'dashboard/index',
            [
                'title' => (string) Config::get(
                    'app.name',
                    'MiguelOS'
                ),
                'version' => (string) Config::get(
                    'app.version',
                    '0.0.4'
                ),
                'projects' => $projects,
                'summary' => $summary,
                'query' => $query,
                'selectedStatus' => $status,
                'selectedPriority' => $priority,
                'selectedSort' => $sort,
                'selectedDirection' => $direction,
                'captureError' => null,
                'captureTitle' => '',
            ]
        );
    }

    public function capture(
        Request $request
    ): Response {
        $title = trim(
            (string) $request->input(
                'title',
                ''
            )
        );

        try {
            $this->taskService->create([
                'title' => $title,
                'description' => '',
                'project_id' => '',
                'status' => 'inbox',
                'priority' => 3,
                'estimated_minutes' => '',
                'start_date' => '',
                'due_date' => '',
                'position' => 0,
            ]);
        } catch (InvalidArgumentException $exception) {
            return $this->captureError(
                $request,
                $title,
                $exception->getMessage()
            );
        }

        return $this->redirect('/');
    }

    private function captureError(
        Request $request,
        string $title,
        string $message
    ): Response {
        $query = trim(
            (string) $request->query('q', '')
        );

        $status = trim(
            (string) $request->query('status', '')
        );

        $priorityValue = trim(
            (string) $request->query('priority', '')
        );

        $priority = $priorityValue !== ''
            ? (int) $priorityValue
            : null;

        $sort = trim(
            (string) $request->query(
                'sort',
                'priority'
            )
        );

        $direction = trim(
            (string) $request->query(
                'direction',
                'asc'
            )
        );

        $projects = $this->projectService->projects(
            $query,
            $status,
            $priority,
            $sort,
            $direction
        );

        $allProjects = $this->projectService->projects(
            '',
            '',
            null,
            'priority',
            'asc'
        );

        return $this->view(
            'dashboard/index',
            [
                'title' => (string) Config::get(
                    'app.name',
                    'MiguelOS'
                ),
                'version' => (string) Config::get(
                    'app.version',
                    '0.0.4'
                ),
                'projects' => $projects,
                'summary' => DashboardPresenter::summary(
                    $allProjects
                ),
                'query' => $query,
                'selectedStatus' => $status,
                'selectedPriority' => $priority,
                'selectedSort' => $sort,
                'selectedDirection' => $direction,
                'captureError' => $message,
                'captureTitle' => $title,
            ],
            422
        );
    }
}