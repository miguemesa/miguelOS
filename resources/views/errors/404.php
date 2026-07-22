<h1>
    <?= htmlspecialchars(
        (string) ($title ?? 'No encontrado'),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</h1>

<p class="lead">
    <?= htmlspecialchars(
        (string) (
            $message
            ?? 'El recurso solicitado no existe.'
        ),
        ENT_QUOTES,
        'UTF-8'
    ) ?>
</p>

<p>
    <a href="/" class="btn btn-primary">
        Volver al inicio
    </a>
</p>