<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

final class Migrator
{
    public function __construct(
        private readonly string $migrationsPath
    ) {
    }

    public function run(): array
    {
        if (!is_dir($this->migrationsPath)) {
            throw new RuntimeException(
                'No existe la carpeta de migraciones.'
            );
        }

        $this->createMigrationsTable();

        $files = glob(
            rtrim($this->migrationsPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . '*.sql'
        );

        if ($files === false) {
            throw new RuntimeException(
                'No fue posible leer las migraciones.'
            );
        }

        sort($files);

        $executed = [];

        foreach ($files as $file) {
            $migration = basename($file);

            if ($this->hasRun($migration)) {
                continue;
            }

            $sql = file_get_contents($file);

            if ($sql === false || trim($sql) === '') {
                throw new RuntimeException(
                    "La migración {$migration} está vacía."
                );
            }

            Database::connection()->exec($sql);

            Database::query(
                'INSERT INTO migrations (migration)
     VALUES (:migration)',
                [
                    'migration' => $migration,
                ]
            );

            $executed[] = $migration;
        }

        return $executed;
    }

    private function createMigrationsTable(): void
    {
        Database::connection()->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB
              DEFAULT CHARSET=utf8mb4
              COLLATE=utf8mb4_unicode_ci'
        );
    }

    private function hasRun(string $migration): bool
    {
        $result = Database::fetch(
            'SELECT id
             FROM migrations
             WHERE migration = :migration
             LIMIT 1',
            [
                'migration' => $migration,
            ]
        );

        return $result !== null;
    }
}