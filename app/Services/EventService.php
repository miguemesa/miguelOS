<?php

declare(strict_types=1);

namespace App\Services;
use App\Models\Event;

use App\Repositories\EventRepository;
use JsonException;

final class EventService
{
    public function __construct(
        private readonly EventRepository $repository = new EventRepository()
    ) {
    }

    /**
     * @throws JsonException
     */
    public function record(
        string $entityType,
        int $entityId,
        string $eventType,
        ?string $fromValue = null,
        ?string $toValue = null,
        ?string $note = null,
        array $metadata = []
    ): int {
        $metadataJson = json_encode(
            $metadata,
            JSON_THROW_ON_ERROR
            | JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
        );

        return $this->repository->insert([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'event_type' => $eventType,
            'from_value' => $fromValue,
            'to_value' => $toValue,
            'note' => $note,
            'metadata' => $metadataJson,
        ]);
    }

    /**
     * @return array<Event>
     */
    public function history(
        string $entityType,
        int $entityId
    ): array {
        return $this->repository->forEntity(
            $entityType,
            $entityId
        );
    }
}