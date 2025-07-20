<?php

declare(strict_types=1);

namespace App\Models\Domain;

use App\Models\Data\StubField;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @implements \IteratorAggregate<int, \App\Models\Data\StubField>
 */
readonly class Stub implements IteratorAggregate, JsonSerializable
{
    /**
     * @param StubField[] $fields
     */
    private function __construct(
        private array $fields,
    ) {
    }

    public static function create(StubField ...$fields): self
    {
        return new self($fields);
    }

    #[\Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->fields);
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     * @return array<string, mixed>[]
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        $results = [];

        foreach ($this->fields as $field) {
            $results += $field->toArray();
        }

        return $results;
    }
}
