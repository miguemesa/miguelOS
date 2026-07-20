<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Config
{
    private static array $items = [];

    public static function load(string $configPath): void
    {
        if (!is_dir($configPath)) {
            throw new RuntimeException("No existe el directorio de configuración: {$configPath}");
        }

        $configFiles = glob(rtrim($configPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.php');

        if ($configFiles === false) {
            throw new RuntimeException('No fue posible leer los archivos de configuración.');
        }

        foreach ($configFiles as $file) {
            $key = pathinfo($file, PATHINFO_FILENAME);
            $value = require $file;

            if (!is_array($value)) {
                throw new RuntimeException("El archivo de configuración {$file} debe devolver un arreglo.");
            }

            self::$items[$key] = $value;
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}
