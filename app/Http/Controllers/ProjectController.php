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
            'projects/create',
            [
                'title' => 'Nuevo proyecto',
                'errors' => [],
                'old' => [
                    'name' => '',
                    'description' => '',
                    'status' => 'idea',
                    'priority' => 3,
                ],
            ]
        );
    }

    public function store(Request $request): Response
    {
        $data = [
            'name' => $request->input('name', ''),
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
        ];

        try {
            $this->service->create($data);
        } catch (InvalidArgumentException $exception) {
            return $this->view(
                'projects/create',
                [
                    'title' => 'Nuevo proyecto',
                    'errors' => [
                        $exception->getMessage(),
                    ],
                    'old' => $data,
                ],
                422
            );
        }

        return $this->redirect('/');
    }
}