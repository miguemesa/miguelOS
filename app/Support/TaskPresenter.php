<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Task;
use DateTimeImmutable;

final class TaskPresenter
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

    public static function isCapture(
        Task $task
    ): bool {
        return
            $task->status === 'inbox'
            && $task->project_id === null
            && $task->estimated_minutes === null
            && $task->start_date === null
            && $task->due_date === null;
    }

    public static function captureAge(
        Task $task
    ): array {
        $capturedAt = self::createDateTime(
            $task->created_at
        );

        if ($capturedAt === null) {
            return [
                'label' => 'Fecha de captura desconocida',
                'text_class' => 'text-body-secondary',
                'days' => null,
                'state' => 'unknown',
            ];
        }

        $now = new DateTimeImmutable();

        $capturedDay = $capturedAt->setTime(
            0,
            0
        );

        $today = $now->setTime(
            0,
            0
        );

        $days = (int) $capturedDay
            ->diff($today)
            ->format('%r%a');

        if ($days < 0) {
            return [
                'label' => sprintf(
                    'Capturada el %d %s %d',
                    (int) $capturedAt->format('j'),
                    self::MONTHS[
                    (int) $capturedAt->format('n')
                    ],
                    (int) $capturedAt->format('Y')
                ),
                'text_class' => 'text-body-secondary',
                'days' => $days,
                'state' => 'future',
            ];
        }

        if ($days === 0) {
            return [
                'label' => sprintf(
                    'Capturada hoy, %s',
                    $capturedAt->format('H:i')
                ),
                'text_class' => 'text-body-secondary',
                'days' => 0,
                'state' => 'today',
            ];
        }

        if ($days === 1) {
            return [
                'label' => 'Capturada ayer',
                'text_class' => 'text-body-secondary',
                'days' => 1,
                'state' => 'yesterday',
            ];
        }

        if ($days < 7) {
            return [
                'label' => sprintf(
                    'Capturada hace %d días',
                    $days
                ),
                'text_class' => 'text-body-secondary',
                'days' => $days,
                'state' => 'recent',
            ];
        }

        if ($days < 30) {
            $weeks = intdiv(
                $days,
                7
            );

            return [
                'label' => $weeks === 1
                    ? 'Capturada hace 1 semana'
                    : sprintf(
                        'Capturada hace %d semanas',
                        $weeks
                    ),
                'text_class' => 'text-warning-emphasis',
                'days' => $days,
                'state' => 'aging',
            ];
        }

        return [
            'label' => sprintf(
                'Capturada el %d %s %d',
                (int) $capturedAt->format('j'),
                self::MONTHS[
                (int) $capturedAt->format('n')
                ],
                (int) $capturedAt->format('Y')
            ),
            'text_class' => 'text-danger-emphasis',
            'days' => $days,
            'state' => 'stale',
        ];
    }

    public static function formatDate(
        ?string $value
    ): string {
        if ($value === null || trim($value) === '') {
            return 'Sin definir';
        }

        $date = self::createDate(
            $value
        );

        if ($date === null) {
            return $value;
        }

        return sprintf(
            '%d %s %d',
            (int) $date->format('j'),
            self::MONTHS[
            (int) $date->format('n')
            ],
            (int) $date->format('Y')
        );
    }

    private static function createDate(
        ?string $value
    ): ?DateTimeImmutable {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = trim($value);

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

    private static function createDateTime(
        ?string $value
    ): ?DateTimeImmutable {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d H:i:s.u',
            DateTimeImmutable::ATOM,
        ];

        foreach ($formats as $format) {
            $date = DateTimeImmutable::createFromFormat(
                $format,
                $value
            );

            $errors = DateTimeImmutable::getLastErrors();

            $hasErrors = is_array($errors)
                && (
                    $errors['warning_count'] > 0
                    || $errors['error_count'] > 0
                );

            if (
                $date !== false
                && !$hasErrors
            ) {
                return $date;
            }
        }

        return null;
    }
}