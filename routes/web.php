<?php

declare(strict_types=1);

use App\Controllers\DashboardController;
use App\Core\Request;
use App\Core\Response;

/** @var App\Core\Router $router */

$router->get('/', function () {

    return (new DashboardController())->index();

});

$router->get('/salud', function () {

    return Response::json([

        'status' => 'ok',

        'application' => 'MiguelOS'

    ]);

});