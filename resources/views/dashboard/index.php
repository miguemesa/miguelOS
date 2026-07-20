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

$query = trim(
    (string) ($query ?? '')
);

$hasSearch = $query !== '';
?>

<div class="mb-4">

    <p class="text-uppercase text-secondary small mb-2">
        Panel principal
    </p>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-4">

        <div>

            <h1 class="mb-2">
                Proyectos
            </h1>

            <p class="lead text-secondary mb-0">

                <?php if ($hasSearch): ?>

                    Resultados para
                    <strong>
                        “<?= htmlspecialchars(
                            $query,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>”
                    </strong>

                <?php else: ?>

                    Una vista general de los proyectos registrados en MiguelOS.

                <?php endif; ?>

            </p>

        </div>

        <form
                method="get"
                action="/"
                class="d-flex gap-2"
                role="search"
        >

            <div>

                <label
                        for="project-search"
                        class="visually-hidden"
                >
                    Buscar proyectos
                </label>

                <input
                        type="search"
                        class="form-control"
                        id="project-search"
                        name="q"
                        value="<?= htmlspecialchars(
                            $query,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        placeholder="Buscar proyectos"
                        maxlength="150"
                >

            </div>

            <button
                    type="submit"
                    class="btn btn-primary"
            >
                Buscar
            </button>

            <?php if ($hasSearch): ?>

                <a
                        href="/"
                        class="btn btn-outline-secondary"
                >
                    Limpiar
                </a>

            <?php endif; ?>

        </form>

    </div>

</div>

<?php if (empty($projects)): ?>

    <div class="card border-0 shadow-sm">

        <div class="card-body py-5 text-center">

            <?php if ($hasSearch): ?>

                <h2 class="h4">
                    No encontramos proyectos
                </h2>

                <p class="text-secondary mb-4">
                    No hay coincidencias para
                    <strong>
                        “<?= htmlspecialchars(
                            $query,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>”
                    </strong>.
                </p>

                <a
                        href="/"
                        class="btn btn-outline-primary"
                >
                    Ver todos los proyectos
                </a>

            <?php else: ?>

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

            <?php endif; ?>

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