<?php

declare(strict_types=1);

$applicationName = (string) \App\Core\Config::get(
    'app.name',
    'MiguelOS'
);

$version = (string) \App\Core\Config::get(
    'app.version',
    '0.0.4'
);

$basePath = rtrim(
    (string) \App\Core\Config::get(
        'app.base_path',
        ''
    ),
    '/'
);

$pageTitle = isset($title) && $title !== ''
    ? $title . ' · ' . $applicationName
    : $applicationName;

$homeUrl = $basePath !== ''
    ? $basePath . '/'
    : '/';

$createProjectUrl = $basePath
    . '/proyectos/crear';
?>
<!doctype html>

<html lang="es">

<head>

    <meta charset="utf-8">

    <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
    >

    <title>
        <?= htmlspecialchars(
            $pageTitle,
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </title>

    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >

</head>

<body class="bg-light d-flex flex-column min-vh-100">

<header>

    <nav
            class="navbar navbar-expand-lg bg-white border-bottom"
            aria-label="Navegación principal"
    >

        <div class="container">

            <a
                    class="navbar-brand fw-semibold"
                    href="<?= htmlspecialchars(
                        $homeUrl,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
            >
                <?= htmlspecialchars(
                    $applicationName,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </a>

            <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavigation"
                    aria-controls="mainNavigation"
                    aria-expanded="false"
                    aria-label="Mostrar navegación"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                    class="collapse navbar-collapse"
                    id="mainNavigation"
            >

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">

                        <a
                                class="nav-link"
                                href="<?= htmlspecialchars(
                                    $homeUrl,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                        >
                            Inicio
                        </a>

                    </li>

                </ul>

                <a
                        class="btn btn-primary"
                        href="<?= htmlspecialchars(
                            $createProjectUrl,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                >
                    Nuevo proyecto
                </a>

            </div>

        </div>

    </nav>

</header>

<main class="flex-grow-1">

    <div class="container py-4 py-lg-5">

        <?= $content ?>

    </div>

</main>

<footer class="bg-white border-top">

    <div
            class="container py-3 d-flex flex-column flex-sm-row justify-content-between gap-2"
    >

        <small class="text-secondary">
            <?= htmlspecialchars(
                $applicationName,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </small>

        <small class="text-secondary">
            Versión
            <?= htmlspecialchars(
                $version,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </small>

    </div>

</footer>

<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>