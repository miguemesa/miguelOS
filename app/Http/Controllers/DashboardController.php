<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Config;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\ProjectService;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly ProjectService $service = new ProjectService()
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

        $projects = $this->service->projects(
            $query,
            $status,
            $priority
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
                'query' => $query,
                'selectedStatus' => $status,
                'selectedPriority' => $priority,
            ]
        );
    }
}