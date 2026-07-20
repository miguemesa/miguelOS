<h1>MiguelOS</h1>

<p class="lead">
    Sistema iniciado correctamente.
</p>

<hr>

<p>

    Versión:

    <strong>

        <?= htmlspecialchars($version) ?>

    </strong>

</p>

<hr>

<h3>Proyectos</h3>

<?php if (empty($projects)): ?>

    <p>No existen proyectos.</p>

<?php else: ?>

    <ul>

        <?php foreach ($projects as $project): ?>

            <li>

                <?= htmlspecialchars($project->name) ?>

            </li>

        <?php endforeach; ?>

    </ul>

<?php endif; ?>

<p>
    <a
            href="/proyectos/crear"
            class="btn btn-primary"
    >
        Nuevo proyecto
    </a>
</p>
