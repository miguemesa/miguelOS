<?php

declare(strict_types=1);

namespace App\Support;
use App\Domain\EventType;
use App\Models\Event;
use DateTimeImmutable;

final class EventPresenter
{
    public static function title(Event $event): string
    {
        return match ($event->event_type) {
            EventType::CREATED => 'Proyecto creado',
            EventType::UPDATED => 'Proyecto actualizado',
            EventType::STATUS_CHANGED => 'Estado actualizado',
            EventType::PRIORITY_CHANGED => 'Prioridad actualizada',
            EventType::COMPLETED => 'Proyecto completado',
            EventType::CANCELLED => 'Proyecto cancelado',
            EventType::FOLLOW_UP => 'Seguimiento registrado',
            EventType::RESOLVED => 'Evento resuelto',
            EventType::NOTE_ADDED => 'Nota agregada',
            default => self::humanize($event->event_type),
        };
    }

    public static function badgeClass(Event $event): string
    {
        return match ($event->event_type) {
            EventType::CREATED => 'success',
            EventType::STATUS_CHANGED => 'primary',
            EventType::PRIORITY_CHANGED => 'warning',
            EventType::UPDATED => 'secondary',
            default => 'light',
        };
    }

    public static function icon(Event $event): string
    {
        return match ($event->event_type) {
            EventType::CREATED => '●',
            EventType::STATUS_CHANGED => '◉',
            EventType::PRIORITY_CHANGED => '◆',
            EventType::UPDATED => '✎',
            default => '•',
        };
    }

    public static function date(Event $event): string
    {
        $date = new DateTimeImmutable(
            $event->occurred_at
        );

        return $date->format('d M Y H:i');
    }

    private static function humanize(string $value): string
    {
        $value = str_replace(
            ['_', '-'],
            ' ',
            trim($value)
        );

        return ucfirst($value);
    }

    private function __construct()
    {
    }
}