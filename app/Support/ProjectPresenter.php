<?php

declare(strict_types=1);

namespace App\Support;

use DateTimeImmutable;

final class ProjectPresenter
{
    private const MONTHS = [
        1 => 'ene',
        2 => 'feb',
        3 => 'mar',
        4 => 'abr',
        5 => 'may',
        6 => 'jun',
        7 => 'jul',
        8 => 'ago',
        9 => 'sep',
        10 => 'oct',
        11 => 'nov',
        12 => 'dic',
    ];

    public static function formatDate(
        ?string $value
    ): string {
        if ($value === null || trim($value) === '') {
            return 'Sin definir';
        }

        $date = self::createDate($value);

        if ($date === null) {
            return $value;
        }

        return sprintf(
            '%d %s %d',
            (int) $date->format('j'),
            self::MONTHS[(int) $date->format('n')],
            (int) $date->format('Y')
        );
    }

    private static function createDate(
        ?string $value
    ): ?DateTimeImmutable {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $value
        );

        $errors = DateTimeImmutable::getLastErrors();

        $hasErrors = is_array($errors)
            && (
                $errors['warning_count'] > 0
                || $errors['error_count'] > 0
            );

        if (
            $date === false
            || $hasErrors
            || $date->format('Y-m-d') !== $value
        ) {
            return null;
        }

        return $date;
    }
}