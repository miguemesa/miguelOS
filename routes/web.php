<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Response;
use App\Http\Controllers\DashboardController;
use App\Core\Request;
use App\Http\Controllers\ProjectController;

/** @var App\Core\Router $router */

$router->get('/', static function (): Response {
    return (new DashboardController())->index();
});

$router->get('/salud', static function (): Response {
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
});

$router->get(
    '/proyectos/crear',
    static function (): Response {
        return (new ProjectController())->create();
    }
);

$router->post(
    '/proyectos',
    static function (Request $request): Response {
        return (new ProjectController())->store($request);
    }
);



