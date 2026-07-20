<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class View
{
    public static function render(string $view, array $data = []): string
    {
        $viewFile = dirname(__DIR__, 2)
            . '/resources/views/'
            . $view
            . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("La vista {$view} no existe.");
        }

        extract($data, EXTR_SKIP);

        ob_start();

        require $viewFile;

        $content = ob_get_clean();

        $layout = dirname(__DIR__, 2)
            . '/resources/layouts/app.php';

        ob_start();

        require $layout;

        return ob_get_clean();
    }
}