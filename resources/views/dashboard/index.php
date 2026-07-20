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

$selectedStatus = trim(
    (string) ($selectedStatus ?? '')
);

$selectedPriority = isset($selectedPriority)
&& $selectedPriority !== null
    ? (int) $selectedPriority
    : null;

$hasSearch = $query !== '';

$hasFilters = $selectedStatus !== ''
    || $selectedPriority !== null;

$hasCriteria = $hasSearch || $hasFilters;
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

                <?php if ($hasCriteria): ?>

                    <?= count($projects) ?>

                    <?= count($projects) === 1
                        ? 'proyecto encontrado'
                        : 'proyectos encontrados' ?>

                    <?php if ($hasSearch): ?>

                        para

                        <strong>
                            “<?= htmlspecialchars(
                                $query,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>”
                        </strong>

                    <?php endif; ?>

                <?php else: ?>

                    Una vista general de los proyectos registrados en MiguelOS.

                <?php endif; ?>

            </p>

        </div>

        <form
                method="get"
                action="/"
                class="row g-2 align-items-end"
                role="search"
        >

            <div class="col-12 col-md">

                <label
                        for="project-search"
                        class="form-label small text-secondary"
                >
                    Buscar
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
                        placeholder="Nombre o descripción"
                        maxlength="150"
                >

            </div>

            <div class="col-12 col-sm-6 col-md-auto">

                <label
                        for="project-status"
                        class="form-label small text-secondary"
                >
                    Estado
                </label>

                <select
                        class="form-select"
                        id="project-status"
                        name="status"
                >

                    <option value="">
                        Todos
                    </option>

                    <?php foreach ($statusLabels as $value => $label): ?>

                        <option
                                value="<?= htmlspecialchars(
                                    $value,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            <?= $selectedStatus === $value
                                ? 'selected'
                                : '' ?>
                        >
                            <?= htmlspecialchars(
                                $label,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-12 col-sm-6 col-md-auto">

                <label
                        for="project-priority"
                        class="form-label small text-secondary"
                >
                    Prioridad
                </label>

                <select
                        class="form-select"
                        id="project-priority"
                        name="priority"
                >

                    <option value="">
                        Todas
                    </option>

                    <?php for ($priority = 1; $priority <= 5; $priority++): ?>

                        <option
                                value="<?= $priority ?>"
                            <?= $selectedPriority === $priority
                                ? 'selected'
                                : '' ?>
                        >
                            <?= $priority ?>
                        </option>

                    <?php endfor; ?>

                </select>

            </div>

            <div class="col-12 col-md-auto">

                <button
                        type="submit"
                        class="btn btn-primary w-100"
                >
                    Aplicar
                </button>

            </div>

            <?php if ($hasCriteria): ?>

                <div class="col-12 col-md-auto">

                    <a
                            href="/"
                            class="btn btn-outline-secondary w-100"
                    >
                        Limpiar
                    </a>

                </div>

            <?php endif; ?>

        </form>

    </div>

</div>

<?php if (empty($projects)): ?>

    <div class="card border-0 shadow-sm">

        <div class="card-body py-5 text-center">

            <?php if ($hasCriteria): ?>

                <h2 class="h4">
                    No encontramos proyectos
                </h2>

                <p class="text-secondary mb-4">
                    Ningún proyecto coincide con los criterios seleccionados.
                </p>

                <a
                        href="/"
                        class="btn btn-outline-primary"
                >
                    Limpiar búsqueda y filtros
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