<?php

declare(strict_types=1);

namespace App\Domain;

final class EntityType
{
    public const PROJECT = 'project';
    public const TASK = 'task';
    public const COMMITMENT = 'commitment';
    public const MILESTONE = 'milestone';
    public const PRODUCTION = 'production';
    public const PRACTICE = 'practice';

    private function __construct()
    {
    }
}