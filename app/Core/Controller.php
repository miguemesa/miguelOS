<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(
        string $view,
        array $data = [],
        int $status = 200
    ): Response {
        return Response::html(
            View::render($view, $data),
            $status
        );
    }

    protected function json(array $data, int $status = 200): Response
    {
        return Response::json($data, $status);
    }

    protected function redirect(string $url): Response
    {
        return Response::redirect($url);
    }
}