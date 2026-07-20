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
                <a
                        href="/proyectos/<?= (int) $project->id ?>"
                >
                    <?= htmlspecialchars(
                        $project->name,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </a>
            </li>

        <?php endforeach; ?>

    </ul>

<?php endif; ?>
