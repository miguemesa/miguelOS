<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Request;
use App\Core\Response;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

/** @var App\Core\Router $router */

$router->get(
    '/',
    static function (Request $request): Response {
        return (new DashboardController())->index(
            $request
        );
    }
);

$router->get(
    '/salud',
    static function (): Response {
        return Response::json([
            'status' => 'ok',
            'application' => (string) Config::get(
                'app.name',
                'MiguelOS'
            ),
            'version' => (string) Config::get(
                'app.version',
                '0.0.4'
            ),
            'environment' => (string) Config::get(
                'app.environment',
                'production'
            ),
        ]);
    }
);

$router->post(
    '/capturar',
    static function (
        Request $request
    ): Response {
        return (new DashboardController())->capture(
            $request
        );
    }
);

/*
|--------------------------------------------------------------------------
| Tareas
|--------------------------------------------------------------------------
*/

$router->get(
    '/tareas',
    static function (): Response {
        return (new TaskController())->index();
    }
);

$router->get(
    '/tareas/crear',
    static function (): Response {
        return (new TaskController())->create();
    }
);

$router->post(
    '/tareas',
    static function (Request $request): Response {
        return (new TaskController())->store(
            $request
        );
    }
);

$router->post(
    '/tareas/{id}/process',
    static function (
        Request $request,
        string $id
    ): Response {
        return (new TaskController())->process(
            $request,
            (int) $id
        );
    }
);

$router->get(
    '/tareas/{id}',
    static function (
        Request $request,
        string $id
    ): Response {
        return (new TaskController())->show(
            (int) $id
        );
    }
);

/*
|--------------------------------------------------------------------------
| Proyectos
|--------------------------------------------------------------------------
*/

$router->get(
    '/proyectos/crear',
    static function (): Response {
        return (new ProjectController())->create();
    }
);

$router->post(
    '/proyectos',
    static function (Request $request): Response {
        return (new ProjectController())->store(
            $request
        );
    }
);

$router->get(
    '/proyectos/{id}/editar',
    static function (
        Request $request,
        string $id
    ): Response {
        return (new ProjectController())->edit(
            (int) $id
        );
    }
);

$router->post(
    '/proyectos/{id}',
    static function (
        Request $request,
        string $id
    ): Response {
        return (new ProjectController())->update(
            $request,
            (int) $id
        );
    }
);

$router->post(
    '/proyectos/{id}/eliminar',
    static function (
        Request $request,
        string $id
    ): Response {
        return (new ProjectController())->destroy(
            (int) $id
        );
    }
);

$router->get(
    '/proyectos/{id}',
    static function (
        Request $request,
        string $id
    ): Response {
        return (new ProjectController())->show(
            (int) $id
        );
    }
);