<?php

declare(strict_types=1);

use App\Support\TaskPresenter;

/**
 * @var array<int, \App\Models\Task> $captures
 * @var array<int, \App\Models\Task> $plannedTasks
 */

$statusLabels = [
    'inbox' => 'Inbox',
    'next' => 'Siguiente',
    'in_progress' => 'En progreso',
    'waiting' => 'En espera',
    'completed' => 'Terminada',
    'cancelled' => 'Cancelada',
];
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">
                Tareas
            </h1>

            <p class="text-body-secondary mb-0">
                Capturas pendientes y acciones planificadas.
            </p>
        </div>

        <a
                href="/tareas/crear"
                class="btn btn-primary"
        >
            Nueva tarea
        </a>
    </div>

<?php if (
    $captures === []
    && $plannedTasks === []
): ?>
    <div class="alert alert-light border">
        Todavía no hay tareas.
    </div>
<?php else: ?>

    <section class="mb-5">
        <div
                class="d-flex justify-content-between align-items-end mb-3"
        >
            <div>
                <h2 class="h4 mb-1">
                    Bandeja de entrada
                </h2>

                <p class="text-body-secondary mb-0">
                    Capturas pendientes de procesar.
                </p>
            </div>

            <?php if ($captures !== []): ?>
                <span class="badge text-bg-secondary">
                    <?= count($captures) ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if ($captures === []): ?>
            <div class="alert alert-light border mb-0">
                La bandeja de entrada está vacía.
            </div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($captures as $task): ?>
                    <?php
                    $captureAge = TaskPresenter::captureAge(
                        $task
                    );
                    ?>
                    <div class="list-group-item">
                        <div
                                class="d-flex w-100 justify-content-between align-items-start gap-3"
                        >
                            <div class="flex-grow-1">
                                <h3 class="h6 mb-1">
                                    <a
                                            href="/tareas/<?= (int) $task->id ?>"
                                            class="text-decoration-none text-body"
                                    >
                                        <?= htmlspecialchars(
                                            $task->title,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </a>
                                </h3>

                                <small
                                        class="<?= htmlspecialchars(
                                            $captureAge['text_class'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                >
                                    <?= htmlspecialchars(
                                        $captureAge['label'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </small>
                            </div>

                            <form
                                    method="post"
                                    action="/tareas/<?= (int) $task->id ?>/process"
                            >

                                <select
                                        name="project_id"
                                        class="form-select form-select-sm"
                                >
                                    <option value="">
                                        Sin proyecto
                                    </option>

                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= (int) $project->id ?>">
                                            <?= htmlspecialchars(
                                                $project->name,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            <div
                                    class="d-flex flex-wrap justify-content-end align-items-center gap-2 mt-2"
                            >

                            <span class="badge text-bg-light border">
                                Prioridad
                                <?= (int) $task->priority ?>
                            </span>

                                <button
                                        type="submit"
                                        name="action"
                                        value="next"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Convertir en siguiente acción"
                                >
                                    →
                                </button>

                                <button
                                        type="submit"
                                        name="action"
                                        value="complete"
                                        class="btn btn-sm btn-outline-success"
                                        title="Marcar como completada"
                                >
                                    ✓
                                </button>

                                <button
                                        type="submit"
                                        name="action"
                                        value="delete"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Eliminar captura"
                                >
                                    ✕
                                </button>

                            </div>
                            </form>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section>
        <div
                class="d-flex justify-content-between align-items-end mb-3"
        >
            <div>
                <h2 class="h4 mb-1">
                    Tareas planificadas
                </h2>

                <p class="text-body-secondary mb-0">
                    Acciones que ya tienen alguna decisión.
                </p>
            </div>

            <?php if ($plannedTasks !== []): ?>
                <span class="badge text-bg-secondary">
                    <?= count($plannedTasks) ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if ($plannedTasks === []): ?>
            <div class="alert alert-light border mb-0">
                Todavía no hay tareas planificadas.
            </div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($plannedTasks as $task): ?>
                    <?php
                    $statusLabel = $statusLabels[$task->status]
                        ?? $task->status;
                    ?>

                    <a
                            href="/tareas/<?= (int) $task->id ?>"
                            class="list-group-item list-group-item-action"
                    >
                        <div
                                class="d-flex w-100 justify-content-between gap-3"
                        >
                            <div>
                                <h3 class="h6 mb-1">
                                    <?= htmlspecialchars(
                                        $task->title,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </h3>

                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge text-bg-secondary">
                                        <?= htmlspecialchars(
                                            $statusLabel,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                    <span
                                            class="badge text-bg-light border"
                                    >
                                        Prioridad
                                        <?= (int) $task->priority ?>
                                    </span>

                                    <?php if (
                                        $task->estimated_minutes !== null
                                    ): ?>
                                        <span
                                                class="badge text-bg-light border"
                                        >
                                            <?= (int) $task
                                                ->estimated_minutes ?>
                                            min
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if ($task->due_date !== null): ?>
                                <small
                                        class="text-body-secondary text-nowrap"
                                >
                                    <?= htmlspecialchars(
                                        TaskPresenter::formatDate(
                                            $task->due_date
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

<?php endif; ?>