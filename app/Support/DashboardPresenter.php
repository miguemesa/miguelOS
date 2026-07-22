<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Project;
use Throwable;

final class DashboardPresenter
{
    /**
     * @param array<int, Project> $projects
     *
     * @return array{
     *     total: int,
     *     idea: int,
     *     active: int,
     *     paused: int,
     *     completed: int,
     *     archived: int,
     *     overdue: int,
     *     due_today: int,
     *     due_tomorrow: int,
     *     due_soon: int,
     *     without_deadline: int
     * }
     */
    public static function summary(
        array $projects
    ): array {
        $summary = [
            'total' => 0,
            'idea' => 0,
            'active' => 0,
            'paused' => 0,
            'completed' => 0,
            'archived' => 0,
            'overdue' => 0,
            'due_today' => 0,
            'due_tomorrow' => 0,
            'due_soon' => 0,
            'without_deadline' => 0,
        ];

        foreach ($projects as $project) {
            if (!$project instanceof Project) {
                continue;
            }

            $summary['total']++;

            $status = trim(
                (string) ($project->status ?? '')
            );

            if (
                $status !== ''
                && isset($summary[$status])
            ) {
                $summary[$status]++;
            }

            try {
                $deadline = ProjectPresenter::deadline(
                    $project
                );
            } catch (Throwable) {
                continue;
            }

            $deadlineState = (string) (
                $deadline['state'] ?? ''
            );

            switch ($deadlineState) {
                case 'overdue':
                    $summary['overdue']++;
                    break;

                case 'today':
                    $summary['due_today']++;
                    break;

                case 'tomorrow':
                    $summary['due_tomorrow']++;
                    break;

                case 'soon':
                    $summary['due_soon']++;
                    break;

                case 'none':
                    $summary['without_deadline']++;
                    break;
            }
        }

        return $summary;
    }
}