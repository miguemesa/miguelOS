<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

final class Router
{
    private array $routes = [];

    public function __construct(
        private readonly Request $request
    ) {
    }

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function add(
        string $method,
        string $path,
        callable $handler
    ): void {
        $this->routes[strtoupper($method)][] = [
            'path' => $this->normalizePath($path),
            'handler' => $handler,
        ];
    }

    public function dispatch(): Response
    {
        $method = $this->request->method();
        $path = $this->request->path();

        foreach ($this->routes[$method] ?? [] as $route) {
            $parameters = $this->match(
                $route['path'],
                $path
            );

            if ($parameters === null) {
                continue;
            }

            try {
                $result = ($route['handler'])(
                    $this->request,
                    ...$parameters
                );

                if ($result instanceof Response) {
                    return $result;
                }

                if (is_string($result)) {
                    return Response::html($result);
                }

                throw new RuntimeException(
                    'La ruta debe devolver Response o HTML.'
                );
            } catch (Throwable $exception) {
                return $this->errorResponse($exception);
            }
        }

        return Response::html(
            '<h1>404</h1><p>La ruta solicitada no existe.</p>',
            404
        );
    }

    private function match(
        string $routePath,
        string $requestPath
    ): ?array {
        $pattern = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            static fn(array $matches): string =>
                '(?P<' . $matches[1] . '>[^/]+)',
            $routePath
        );

        if (!is_string($pattern)) {
            return null;
        }

        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestPath, $matches) !== 1) {
            return null;
        }

        $parameters = [];

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $parameters[] = $value;
            }
        }

        return $parameters;
    }

    private function errorResponse(Throwable $exception): Response
    {
        if ((bool) Config::get('app.debug', false)) {
            $message = htmlspecialchars(
                $exception->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            );

            $file = htmlspecialchars(
                $exception->getFile(),
                ENT_QUOTES,
                'UTF-8'
            );

            $trace = htmlspecialchars(
                $exception->getTraceAsString(),
                ENT_QUOTES,
                'UTF-8'
            );

            return Response::html(
                "<h1>Error interno</h1>
                <p><strong>{$message}</strong></p>
                <p>{$file}:{$exception->getLine()}</p>
                <pre>{$trace}</pre>",
                500
            );
        }

        return Response::html(
            '<h1>Error interno</h1>
            <p>Ocurrió un problema inesperado.</p>',
            500
        );
    }

    private function normalizePath(string $path): string
    {
        $normalized = '/' . trim($path, '/');

        return $normalized === '//'
            ? '/'
            : $normalized;
    }
}