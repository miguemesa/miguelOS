<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Project;
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

    public static function deadline(
        Project $project
    ): array {
        if ($project->due_date === null) {
            return [
                'label' => 'Sin fecha límite',
                'badge_class' => 'text-bg-secondary',
                'days' => null,
                'state' => 'none',
            ];
        }

        $dueDate = self::createDate(
            $project->due_date
        );

        if ($dueDate === null) {
            return [
                'label' => 'Fecha límite inválida',
                'badge_class' => 'text-bg-secondary',
                'days' => null,
                'state' => 'invalid',
            ];
        }

        if (in_array(
            $project->status,
            ['completed', 'archived'],
            true
        )) {
            return [
                'label' => $project->status === 'completed'
                    ? 'Proyecto completado'
                    : 'Proyecto archivado',
                'badge_class' => 'text-bg-secondary',
                'days' => null,
                'state' => $project->status,
            ];
        }

        $today = new DateTimeImmutable('today');

        $days = (int) $today
            ->diff($dueDate)
            ->format('%r%a');

        if ($days < 0) {
            $elapsedDays = abs($days);

            return [
                'label' => $elapsedDays === 1
                    ? 'Vencido hace 1 día'
                    : sprintf(
                        'Vencido hace %d días',
                        $elapsedDays
                    ),
                'badge_class' => 'text-bg-danger',
                'days' => $days,
                'state' => 'overdue',
            ];
        }

        if ($days === 0) {
            return [
                'label' => 'Vence hoy',
                'badge_class' => 'text-bg-danger',
                'days' => 0,
                'state' => 'today',
            ];
        }

        if ($days === 1) {
            return [
                'label' => 'Vence mañana',
                'badge_class' => 'text-bg-warning',
                'days' => 1,
                'state' => 'tomorrow',
            ];
        }

        if ($days <= 7) {
            return [
                'label' => sprintf(
                    'Vence en %d días',
                    $days
                ),
                'badge_class' => 'text-bg-warning',
                'days' => $days,
                'state' => 'soon',
            ];
        }

        return [
            'label' => sprintf(
                'Restan %d días',
                $days
            ),
            'badge_class' => 'text-bg-success',
            'days' => $days,
            'state' => 'future',
        ];
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