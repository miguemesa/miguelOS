<?php

declare(strict_types=1);

/**
 * @var \App\Models\Task $task
 */

$statusLabels = [
    'inbox' => 'Captura',
    'next' => 'Siguiente',
    'in_progress' => 'En progreso',
    'waiting' => 'En espera',
    'completed' => 'Terminada',
    'cancelled' => 'Cancelada',
];

$statusLabel = $statusLabels[$task->status]
    ?? $task->status;
?>

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <a
                href="/tareas"
                class="text-decoration-none"
        >
            ← Volver a tareas
        </a>

        <h1 class="h2 mt-3 mb-2">
            <?= htmlspecialchars(
                $task->title,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </h1>

        <span class="badge text-bg-secondary">
            <?= htmlspecialchars(
                $statusLabel,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </span>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($task->description !== null): ?>
            <p>
                <?= nl2br(
                    htmlspecialchars(
                        $task->description,
                        ENT_QUOTES,
                        'UTF-8'
                    )
                ) ?>
            </p>
        <?php else: ?>
            <p class="text-body-secondary">
                Sin descripción.
            </p>
        <?php endif; ?>

        <dl class="row mb-0">
            <dt class="col-sm-4">
                Prioridad
            </dt>

            <dd class="col-sm-8">
                <?= (int) $task->priority ?>
            </dd>

            <dt class="col-sm-4">
                Proyecto
            </dt>

            <dd class="col-sm-8">
                <?= $task->project_id !== null
                    ? '#' . (int) $task->project_id
                    : 'Sin proyecto' ?>
            </dd>

            <dt class="col-sm-4">
                Tiempo estimado
            </dt>

            <dd class="col-sm-8">
                <?= $task->estimated_minutes !== null
                    ? (int) $task->estimated_minutes . ' minutos'
                    : 'Sin estimación' ?>
            </dd>

            <dt class="col-sm-4">
                Fecha de inicio
            </dt>

            <dd class="col-sm-8">
                <?= htmlspecialchars(
                    $task->start_date ?? 'Sin fecha',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </dd>

            <dt class="col-sm-4">
                Fecha límite
            </dt>

            <dd class="col-sm-8">
                <?= htmlspecialchars(
                    $task->due_date ?? 'Sin fecha',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </dd>
        </dl>
    </div>
</div>