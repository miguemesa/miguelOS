<?php

declare(strict_types=1);

namespace App\View;

use RuntimeException;
use Throwable;

final class Component
{
    public static function render(
        string $name,
        array $data = []
    ): string {
        $path = self::path($name);

        if (!is_file($path)) {
            throw new RuntimeException(
                sprintf(
                    'El componente "%s" no existe.',
                    $name
                )
            );
        }

        extract(
            $data,
            EXTR_SKIP
        );

        ob_start();

        try {
            require $path;

            return (string) ob_get_clean();
        } catch (Throwable $exception) {
            ob_end_clean();

            throw $exception;
        }
    }

    private static function path(string $name): string
    {
        $normalizedName = trim(
            str_replace('.', '/', $name),
            '/'
        );

        if (
            $normalizedName === ''
            || str_contains($normalizedName, '..')
        ) {
            throw new RuntimeException(
                'El nombre del componente no es válido.'
            );
        }

        return dirname(__DIR__, 2)
            . '/resources/views/components/'
            . $normalizedName
            . '.php';
    }

    private function __construct()
    {
    }
}