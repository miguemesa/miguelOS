<?php

declare(strict_types=1);

namespace App\Core;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

final class Hydrator
{
    /**
     * @template T of object
     *
     * @param class-string<T> $class
     * @param array<string, mixed> $data
     *
     * @return T
     */
    public static function make(string $class, array $data): object
    {
        if (!class_exists($class)) {
            throw new RuntimeException(
                sprintf('La clase %s no existe.', $class)
            );
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(
                sprintf('La clase %s no puede instanciarse.', $class)
            );
        }

        $object = $reflection->newInstance();

        foreach ($data as $propertyName => $value) {
            if (!$reflection->hasProperty($propertyName)) {
                continue;
            }

            $property = $reflection->getProperty($propertyName);

            if ($property->isStatic()) {
                continue;
            }

            self::assign($object, $property, $value);
        }

        return $object;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     * @param array<int, array<string, mixed>> $rows
     *
     * @return array<int, T>
     */
    public static function collection(
        string $class,
        array $rows
    ): array {
        $objects = [];

        foreach ($rows as $row) {
            $objects[] = self::make($class, $row);
        }

        return $objects;
    }

    private static function assign(
        object $object,
        ReflectionProperty $property,
        mixed $value
    ): void {
        $type = $property->getType();

        if (
            $value !== null
            && $type instanceof ReflectionNamedType
            && $type->isBuiltin()
        ) {
            $value = self::castValue($type->getName(), $value);
        }

        $property->setValue($object, $value);
    }

    private static function castValue(
        string $type,
        mixed $value
    ): mixed {
        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => self::castBoolean($value),
            'string' => (string) $value,
            'array' => (array) $value,
            default => $value,
        };
    }

    private static function castBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return filter_var(
            $value,
            FILTER_VALIDATE_BOOL
        );
    }
}