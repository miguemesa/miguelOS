<?php
declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'MiguelOS',
    'environment' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),
    'url' => $_ENV['APP_URL'] ?? '',
    'base_path' => $_ENV['APP_BASE_PATH'] ?? '',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'America/Mexico_City',
];
