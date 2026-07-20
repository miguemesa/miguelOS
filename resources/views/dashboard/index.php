<?php

declare(strict_types=1);

$statusLabels = [
    'idea' => 'Idea',
    'active' => 'Activo',
    'paused' => 'Pausado',
    'completed' => 'Completado',
    'cancelled' => 'Cancelado',
];

$statusClasses = [
    'idea' => 'text-bg-secondary',
    'active' => 'text-bg-success',
    'paused' => 'text-bg-warning',
    'completed' => 'text-bg-primary',
    'cancelled' => 'text-bg-dark',
];
?>

<div class="mb-4">

    <p class="text-uppercase text-secondary small mb-2">
        Panel principal
    </p>

    <h1 class="mb-2">
        Proyectos
    </h1>

    <p class="lead text-secondary mb-0">
        Una vista general de los proyectos registrados en MiguelOS.
    </p>

</div>

<?php if (empty($projects)): ?>

    <div class="card border-0 shadow-sm">

        <div class="card-body py-5 text-center">

            <h2 class="h4">
                Todavía no hay proyectos
            </h2>

            <p class="text-secondary mb-4">
                Crea el primero para comenzar a organizar tu trabajo.
            </p>

            <a
                    href="/proyectos/crear"
                    class="btn btn-primary"
            >
                Crear proyecto
            </a>

        </div>

    </div>

<?php else: ?>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">

        <?php foreach ($projects as $project): ?>

            <?php
            $statusLabel = $statusLabels[$project->status]
                ?? $project->status;

            $statusClass = $statusClasses[$project->status]
                ?? 'text-bg-secondary';

            $description = trim(
                (string) ($project->description ?? '')
            );

            $shortDescription = $description !== ''
                ? mb_strimwidth(
                    $description,
                    0,
                    140,
                    '…',
                    'UTF-8'
                )
                : 'Este proyecto todavía no tiene descripción.';
            ?>

            <div class="col">

                <article class="card h-100 border-0 shadow-sm">

                    <div class="card-body d-flex flex-column">

                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">

                            <span
                                    class="badge <?= htmlspecialchars(
                                        $statusClass,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                            >
                                <?= htmlspecialchars(
                                    $statusLabel,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </span>

                            <span
                                    class="small text-secondary"
                                    aria-label="Prioridad <?= (int) $project->priority ?> de 5"
                                    title="Prioridad <?= (int) $project->priority ?> de 5"
                            >
                                <?= str_repeat(
                                    '●',
                                    (int) $project->priority
                                ) ?>

                                <span class="opacity-25">
                                    <?= str_repeat(
                                        '○',
                                        5 - (int) $project->priority
                                    ) ?>
                                </span>
                            </span>

                        </div>

                        <h2 class="h5 card-title">

                            <a
                                    href="/proyectos/<?= (int) $project->id ?>"
                                    class="text-decoration-none text-body"
                            >
                                <?= htmlspecialchars(
                                    $project->name,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </a>

                        </h2>

                        <p class="card-text text-secondary flex-grow-1">
                            <?= htmlspecialchars(
                                $shortDescription,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                        <div class="d-flex gap-2 pt-3 border-top">

                            <a
                                    href="/proyectos/<?= (int) $project->id ?>"
                                    class="btn btn-primary btn-sm"
                            >
                                Abrir
                            </a>

                            <a
                                    href="/proyectos/<?= (int) $project->id ?>/editar"
                                    class="btn btn-outline-secondary btn-sm"
                            >
                                Editar
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<div class="mt-5 pt-3 border-top">

    <small class="text-secondary">
        MiguelOS versión
        <?= htmlspecialchars(
            (string) $version,
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </small>

</div>