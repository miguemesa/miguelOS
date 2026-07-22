<?php

declare(strict_types=1);

use App\Support\EventPresenter;

/**
 * @var array<App\Models\Event> $events
 */
?>

<?php if (empty($events)): ?>

    <div class="alert alert-light border">
        No hay eventos registrados.
    </div>

<?php else: ?>

    <div class="list-group">

        <?php foreach ($events as $event): ?>

            <article class="list-group-item">

                <div class="d-flex justify-content-between align-items-start">

                    <div class="me-3">

                        <span class="badge text-bg-<?= htmlspecialchars(
                            EventPresenter::badgeClass($event),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>">
                            <?= htmlspecialchars(
                                EventPresenter::icon($event),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </span>

                        <strong class="ms-2">
                            <?= htmlspecialchars(
                                EventPresenter::title($event),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>

                        <?php if ($event->note !== null): ?>

                            <div class="mt-2 text-muted">

                                <?= htmlspecialchars(
                                    $event->note,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        <?php endif; ?>

                        <?php if (
                            $event->from_value !== null
                            || $event->to_value !== null
                        ): ?>

                            <div class="small mt-2">

                                <?php if ($event->from_value !== null): ?>

                                    <span>

                                        <?= htmlspecialchars(
                                            $event->from_value,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                                <?php if (
                                    $event->from_value !== null
                                    && $event->to_value !== null
                                ): ?>

                                    <span class="mx-2">→</span>

                                <?php endif; ?>

                                <?php if ($event->to_value !== null): ?>

                                    <span>

                                        <?= htmlspecialchars(
                                            $event->to_value,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                    <small class="text-muted text-nowrap">

                        <?= htmlspecialchars(
                            EventPresenter::date($event),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </small>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>