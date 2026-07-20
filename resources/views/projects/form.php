<?php

declare(strict_types=1);

$isEditing = isset($project) && $project !== null;

$formAction = $isEditing
    ? '/proyectos/' . (int) $project->id
    : '/proyectos';

$heading = $isEditing
    ? 'Editar proyecto'
    : 'Nuevo proyecto';

$cancelUrl = $isEditing
    ? '/proyectos/' . (int) $project->id
    : '/';
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="mb-1">
            <?= htmlspecialchars(
                $heading,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </h1>

        <?php if ($isEditing): ?>

            <p class="text-secondary mb-0">
                Modifica la información del proyecto.
            </p>

        <?php else: ?>

            <p class="text-secondary mb-0">
                Registra un nuevo proyecto en MiguelOS.
            </p>

        <?php endif; ?>

    </div>

</div>

<?php if (!empty($errors)): ?>

    <div class="alert alert-danger">

        <ul class="mb-0">

            <?php foreach ($errors as $error): ?>

                <li>
                    <?= htmlspecialchars(
                        (string) $error,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </li>

            <?php endforeach; ?>

        </ul>

    </div>

<?php endif; ?>

<form method="post" action="<?= htmlspecialchars(
    $formAction,
    ENT_QUOTES,
    'UTF-8'
) ?>">

    <div class="mb-3">

        <label for="name" class="form-label">
            Nombre
        </label>

        <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                maxlength="180"
                required
                value="<?= htmlspecialchars(
                    (string) ($old['name'] ?? ''),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
        >

    </div>

    <div class="mb-3">

        <label for="description" class="form-label">
            Descripción
        </label>

        <textarea
                class="form-control"
                id="description"
                name="description"
                rows="5"
        ><?= htmlspecialchars(
                (string) ($old['description'] ?? ''),
                ENT_QUOTES,
                'UTF-8'
            ) ?></textarea>

    </div>

    <div class="mb-3">

        <label for="status" class="form-label">
            Estado
        </label>

        <?php
        $selectedStatus = (string) (
            $old['status'] ?? 'idea'
        );
        ?>

        <select
                class="form-select"
                id="status"
                name="status"
        >

            <option
                    value="idea"
                <?= $selectedStatus === 'idea'
                    ? 'selected'
                    : '' ?>
            >
                Idea
            </option>

            <option
                    value="active"
                <?= $selectedStatus === 'active'
                    ? 'selected'
                    : '' ?>
            >
                Activo
            </option>

            <option
                    value="paused"
                <?= $selectedStatus === 'paused'
                    ? 'selected'
                    : '' ?>
            >
                Pausado
            </option>

            <option
                    value="completed"
                <?= $selectedStatus === 'completed'
                    ? 'selected'
                    : '' ?>
            >
                Completado
            </option>

            <option
                    value="archived"
                <?= $selectedStatus === 'archived'
                    ? 'selected'
                    : '' ?>
            >
                Archivado
            </option>

        </select>

    </div>

    <div class="mb-3">

        <label for="priority" class="form-label">
            Prioridad
        </label>

        <select
                class="form-select"
                id="priority"
                name="priority"
        >

            <?php for ($priority = 1; $priority <= 5; $priority++): ?>

                <option
                        value="<?= $priority ?>"
                    <?= (int) ($old['priority'] ?? 3) === $priority
                        ? 'selected'
                        : '' ?>
                >
                    <?= $priority ?>
                </option>

            <?php endfor; ?>

        </select>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-12 col-md-6">

            <label
                    for="start_date"
                    class="form-label"
            >
                Fecha de inicio
            </label>

            <input
                    type="date"
                    class="form-control"
                    id="start_date"
                    name="start_date"
                    value="<?= htmlspecialchars(
                        (string) ($old['start_date'] ?? ''),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
            >

            <div class="form-text">
                Puede dejarse vacía.
            </div>

        </div>

        <div class="col-12 col-md-6">

            <label
                    for="due_date"
                    class="form-label"
            >
                Fecha límite
            </label>

            <input
                    type="date"
                    class="form-control"
                    id="due_date"
                    name="due_date"
                    value="<?= htmlspecialchars(
                        (string) ($old['due_date'] ?? ''),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
            >

            <div class="form-text">
                Debe ser igual o posterior a la fecha de inicio.
            </div>

        </div>

    </div>

    <div class="d-flex gap-2">

        <button type="submit" class="btn btn-primary">
            <?= $isEditing
                ? 'Guardar cambios'
                : 'Guardar proyecto' ?>
        </button>

        <a
                href="<?= htmlspecialchars(
                    $cancelUrl,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                class="btn btn-outline-secondary"
        >
            Cancelar
        </a>

    </div>

</form>