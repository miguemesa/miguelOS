<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

final class Router
{
    private array $routes = [];

    public function __construct(private readonly Request $request) {}

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[strtoupper($method)][$this->normalizePath($path)] = $handler;
    }

    public function dispatch(): Response
    {
        $method = $this->request->method();
        $path = $this->request->path();
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            return Response::html('<h1>404</h1><p>La ruta solicitada no existe.</p>', 404);
        }

        try {
            $result = $handler($this->request);

            if ($result instanceof Response) {
                return $result;
            }

            if (is_string($result)) {
                return Response::html($result);
            }

            throw new RuntimeException('La ruta debe devolver Response o HTML.');
        } catch (Throwable $exception) {
            if ((bool) Config::get('app.debug', false)) {
                $message = htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
                $file = htmlspecialchars($exception->getFile(), ENT_QUOTES, 'UTF-8');
                $trace = htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8');

                return Response::html(
                    "<h1>Error interno</h1><p><strong>{$message}</strong></p><p>{$file}:{$exception->getLine()}</p><pre>{$trace}</pre>",
                    500
                );
            }

            return Response::html('<h1>Error interno</h1><p>Ocurrió un problema inesperado.</p>', 500);
        }
    }

    private function normalizePath(string $path): string
    {
        $normalized = '/' . trim($path, '/');
        return $normalized === '//' ? '/' : $normalized;
    }
}
