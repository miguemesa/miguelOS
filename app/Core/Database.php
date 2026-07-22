<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;
use Throwable;

final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $driver = (string) Config::get('database.driver', 'mysql');
        $host = (string) Config::get('database.host', 'localhost');
        $port = (int) Config::get('database.port', 3306);
        $database = (string) Config::get('database.database', '');
        $username = (string) Config::get('database.username', '');
        $password = (string) Config::get('database.password', '');
        $charset = (string) Config::get('database.charset', 'utf8mb4');

        if ($database === '') {
            throw new RuntimeException(
                'La variable DB_DATABASE no está configurada.'
            );
        }

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $driver,
            $host,
            $port,
            $database,
            $charset
        );

        try {
            self::$connection = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'No fue posible conectar con la base de datos.',
                0,
                $exception
            );
        }

        return self::$connection;
    }

    public static function query(
        string $sql,
        array $parameters = []
    ): PDOStatement {
        $statement = self::connection()->prepare($sql);
        $statement->execute($parameters);

        return $statement;
    }

    public static function fetch(
        string $sql,
        array $parameters = []
    ): ?array {
        $result = self::query($sql, $parameters)->fetch();

        return $result === false ? null : $result;
    }

    public static function fetchAll(
        string $sql,
        array $parameters = []
    ): array {
        return self::query($sql, $parameters)->fetchAll();
    }

    public static function execute(
        string $sql,
        array $parameters = []
    ): int {
        return self::query($sql, $parameters)->rowCount();
    }

    public static function lastInsertId(): int
    {
        return (int) self::connection()->lastInsertId();
    }

    public static function transaction(callable $callback): mixed
    {
        $connection = self::connection();

        try {
            $connection->beginTransaction();

            $result = $callback($connection);

            if ($connection->inTransaction()) {
                $connection->commit();
            }

            return $result;
        } catch (Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            throw $exception;
        }
    }

    public static function disconnect(): void
    {
        self::$connection = null;
    }
}