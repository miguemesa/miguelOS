<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Core\Hydrator;
use App\Models\Event;

final class EventRepository
{
    public function insert(array $data): int
    {
        Database::execute(
            "
            INSERT INTO entity_events (
                entity_type,
                entity_id,
                event_type,
                from_value,
                to_value,
                note,
                metadata
            ) VALUES (
                :entity_type,
                :entity_id,
                :event_type,
                :from_value,
                :to_value,
                :note,
                :metadata
            )
            ",
            [
                'entity_type' => $data['entity_type'],
                'entity_id' => $data['entity_id'],
                'event_type' => $data['event_type'],
                'from_value' => $data['from_value'],
                'to_value' => $data['to_value'],
                'note' => $data['note'],
                'metadata' => $data['metadata'],
            ]
        );

        return Database::lastInsertId();
    }

    public function forEntity(
        string $entityType,
        int $entityId
    ): array {

        $rows = Database::fetchAll(
            "
        SELECT
            id,
            entity_type,
            entity_id,
            event_type,
            from_value,
            to_value,
            note,
            metadata,
            occurred_at,
            created_at
        FROM entity_events
        WHERE entity_type = :entity_type
          AND entity_id = :entity_id
        ORDER BY occurred_at ASC,
                 id ASC
        ",
            [
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
            ]
        );

        foreach ($rows as &$row) {

            $row['metadata'] = empty($row['metadata'])
                ? []
                : json_decode(
                    $row['metadata'],
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
        }

        unset($row);

        return Hydrator::collection(
            Event::class,
            $rows
        );
    }
}