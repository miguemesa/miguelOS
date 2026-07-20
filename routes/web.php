<?php
declare(strict_types=1);

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

/** @var Router $router */

$router->get('/', static function (Request $request): Response {
    $path = htmlspecialchars($request->path(), ENT_QUOTES, 'UTF-8');

    return Response::html(
        '<!doctype html>
        <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>MiguelOS</title>
            <style>
                body{font-family:system-ui,sans-serif;margin:0;min-height:100vh;display:grid;place-items:center;background:#f5f5f2;color:#1f2937}
                main{width:min(680px,calc(100% - 3rem));padding:3rem;background:#fff;border-radius:1rem;box-shadow:0 1rem 3rem rgba(0,0,0,.08)}
                h1{margin-top:0} code{background:#f3f4f6;padding:.2rem .4rem;border-radius:.3rem}
            </style>
        </head>
        <body>
            <main>
                <h1>MiguelOS</h1>
                <p>El núcleo de la aplicación está funcionando.</p>
                <p><strong>Versión:</strong> 0.0.2</p>
                <p><strong>Ruta:</strong> <code>' . $path . '</code></p>
            </main>
        </body>
        </html>'
    );
});

$router->get('/salud', static function (): Response {
    return Response::json([
        'status' => 'ok',
        'application' => 'MiguelOS',
        'version' => '0.0.2',
    ]);
});
