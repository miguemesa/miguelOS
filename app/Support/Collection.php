<?php

declare(strict_types=1);

namespace App\Support;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, Countable
{
    protected array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    public function last(): mixed
    {
        if ($this->items === []) {
            return null;
        }

        return $this->items[array_key_last($this->items)];
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}