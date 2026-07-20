<h1>Nuevo proyecto</h1>

<p>
    <a href="/">Volver al inicio</a>
</p>

<?php if (!empty($errors)): ?>

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

<form method="post" action="/proyectos">

    <div class="mb-3">

        <label for="name" class="form-label">
            Nombre
        </label>

        <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            maxlength="150"
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
                value="cancelled"
                <?= $selectedStatus === 'cancelled'
                    ? 'selected'
                    : '' ?>
            >
                Cancelado
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

    <button type="submit" class="btn btn-primary">
        Guardar proyecto
    </button>

</form>