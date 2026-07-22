<?php

declare(strict_types=1);

/**
 * @var \App\Models\Task|null $task
 * @var array<int, \App\Models\Project> $projects
 * @var array<int, string> $errors
 * @var array<string, mixed> $old
 */

$isEditing = $task !== null;

$action = $isEditing
    ? '/tareas/' . (int) $task->id
    : '/tareas';

$statusOptions = [
    'inbox' => 'Captura',
    'next' => 'Siguiente',
    'in_progress' => 'En progreso',
    'waiting' => 'En espera',
    'completed' => 'Terminada',
    'cancelled' => 'Cancelada',
];
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">
                <?= $isEditing
                    ? 'Editar tarea'
                    : 'Nueva tarea' ?>
            </h1>

            <a
                    href="/tareas"
                    class="btn btn-outline-secondary"
            >
                Volver
            </a>
        </div>

        <?php if ($errors !== []): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li>
                            <?= htmlspecialchars(
                                $error,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form
                method="post"
                action="<?= htmlspecialchars(
                    $action,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
        >
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label
                                for="title"
                                class="form-label"
                        >
                            Título
                        </label>

                        <input
                                type="text"
                                id="title"
                                name="title"
                                class="form-control"
                                maxlength="220"
                                required
                                autofocus
                                value="<?= htmlspecialchars(
                                    (string) ($old['title'] ?? ''),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label
                                for="description"
                                class="form-label"
                        >
                            Descripción
                        </label>

                        <textarea
                                id="description"
                                name="description"
                                class="form-control"
                                rows="4"
                        ><?= htmlspecialchars(
                                (string) ($old['description'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                    for="project_id"
                                    class="form-label"
                            >
                                Proyecto
                            </label>

                            <select
                                    id="project_id"
                                    name="project_id"
                                    class="form-select"
                            >
                                <option value="">
                                    Sin proyecto
                                </option>

                                <?php foreach ($projects as $project): ?>
                                    <option
                                            value="<?= (int) $project->id ?>"
                                        <?= (
                                            (string) (
                                                $old['project_id']
                                                ?? ''
                                            )
                                            === (string) $project->id
                                        )
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= htmlspecialchars(
                                            $project->name,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label
                                    for="status"
                                    class="form-label"
                            >
                                Estado
                            </label>

                            <select
                                    id="status"
                                    name="status"
                                    class="form-select"
                            >
                                <?php foreach (
                                    $statusOptions
                                    as $value => $label
                                ): ?>
                                    <option
                                            value="<?= htmlspecialchars(
                                                $value,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                        <?= (
                                            ($old['status'] ?? 'inbox')
                                            === $value
                                        )
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
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                    for="priority"
                                    class="form-label"
                            >
                                Prioridad
                            </label>

                            <select
                                    id="priority"
                                    name="priority"
                                    class="form-select"
                            >
                                <?php for (
                                    $priority = 1;
                                    $priority <= 5;
                                    $priority++
                                ): ?>
                                    <option
                                            value="<?= $priority ?>"
                                        <?= (
                                            (int) (
                                                $old['priority']
                                                ?? 3
                                            )
                                            === $priority
                                        )
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= $priority ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label
                                    for="estimated_minutes"
                                    class="form-label"
                            >
                                Tiempo estimado
                            </label>

                            <div class="input-group">
                                <input
                                        type="number"
                                        id="estimated_minutes"
                                        name="estimated_minutes"
                                        class="form-control"
                                        min="1"
                                        step="1"
                                        value="<?= htmlspecialchars(
                                            (string) (
                                                $old['estimated_minutes']
                                                ?? ''
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                >

                                <span class="input-group-text">
                                    minutos
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                    for="start_date"
                                    class="form-label"
                            >
                                Fecha de inicio
                            </label>

                            <input
                                    type="date"
                                    id="start_date"
                                    name="start_date"
                                    class="form-control"
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $old['start_date']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label
                                    for="due_date"
                                    class="form-label"
                            >
                                Fecha límite
                            </label>

                            <input
                                    type="date"
                                    id="due_date"
                                    name="due_date"
                                    class="form-control"
                                    value="<?= htmlspecialchars(
                                        (string) (
                                            $old['due_date']
                                            ?? ''
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                            >
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end gap-2">
                    <a
                            href="/tareas"
                            class="btn btn-outline-secondary"
                    >
                        Cancelar
                    </a>

                    <button
                            type="submit"
                            class="btn btn-primary"
                    >
                        Guardar tarea
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>