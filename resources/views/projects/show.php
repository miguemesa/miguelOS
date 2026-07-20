<?php

declare(strict_types=1);

function formatProjectDate(?string $value): string
{
    if ($value === null || trim($value) === '') {
        return 'Sin definir';
    }

    $date = DateTimeImmutable::createFromFormat(
        'Y-m-d',
        $value
    );

    if ($date === false) {
        return $value;
    }

    $months = [
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

    return sprintf(
        '%d %s %d',
        (int)$date->format('j'),
        $months[(int)$date->format('n')],
        (int)$date->format('Y')
    );
}


$statusLabels = [
    'idea' => 'Idea',
    'active' => 'Activo',
    'paused' => 'Pausado',
    'completed' => 'Completado',
    'archived' => 'Archivado',
];

$statusLabel = $statusLabels[$project->status]
    ?? $project->status;
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">

    <div>

        <p class="mb-2">

            <a
                    href="/"
                    class="link-secondary text-decoration-none"
            >
                ← Volver a proyectos
            </a>

        </p>

        <h1 class="mb-0">
            <?= htmlspecialchars(
                $project->name,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </h1>

    </div>

    <div class="d-flex gap-2">

        <a
                href="/proyectos/<?= (int) $project->id ?>/editar"
                class="btn btn-outline-primary"
        >
            Editar proyecto
        </a>

        <form
                method="post"
                action="/proyectos/<?= (int) $project->id ?>/eliminar"
                onsubmit="return confirm(
            '¿Eliminar este proyecto? Esta acción no se puede deshacer.'
        );"
        >

            <button
                    type="submit"
                    class="btn btn-outline-danger"
            >
                Eliminar
            </button>

        </form>

    </div>

</div>

<hr class="mb-4">

<div class="row g-4">

    <div class="col-lg-8">

        <section>

            <h2 class="h4 mb-3">
                Descripción
            </h2>

            <?php if (
                $project->description !== null
                && trim($project->description) !== ''
            ): ?>

                <div class="lh-lg">
                    <?= nl2br(
                        htmlspecialchars(
                            $project->description,
                            ENT_QUOTES,
                            'UTF-8'
                        )
                    ) ?>
                </div>

            <?php else: ?>

                <p class="text-secondary">
                    Este proyecto todavía no tiene descripción.
                </p>

            <?php endif; ?>

        </section>

    </div>

    <div class="col-lg-4">

        <aside class="card shadow-sm">

            <div class="card-body">

                <h2 class="h5 card-title mb-3">
                    Información
                </h2>

                <dl class="mb-0">

                    <dt class="text-secondary fw-normal">
                        Estado
                    </dt>

                    <dd class="mb-3">
                        <?= htmlspecialchars(
                            $statusLabel,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </dd>

                    <dt class="text-secondary fw-normal">
                        Prioridad
                    </dt>

                    <dd class="mb-3">
                        <?= (int) $project->priority ?>
                    </dd>

                    <dt class="text-secondary fw-normal">
                        Fecha de inicio
                    </dt>

                    <dd class="mb-3">
                        <?= htmlspecialchars(
                            formatProjectDate(
                                $project->start_date
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </dd>

                    <dt class="text-secondary fw-normal">
                        Fecha límite
                    </dt>

                    <dd class="mb-3">
                        <?= htmlspecialchars(
                            formatProjectDate(
                                $project->due_date
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </dd>

                    <dt class="text-secondary fw-normal">
                        Slug
                    </dt>

                    <dd class="mb-0">
                        <code>
                            <?= htmlspecialchars(
                                $project->slug,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </code>
                    </dd>

                </dl>

            </div>

        </aside>

    </div>

</div>