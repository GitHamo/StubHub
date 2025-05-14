<?php

declare(strict_types=1);

namespace App\Modules\Structure\Domain;

use App\Models\Data\StructureInput;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @implements \IteratorAggregate<int, \App\Models\Data\StructureInput>
 */
readonly class Structure implements IteratorAggregate, JsonSerializable
{
    /**
     * @param StructureInput[] $inputs
     */
    private function __construct(
        private array $inputs,
    ) {
    }

    public static function create(StructureInput ...$inputs): self
    {
        return new self($inputs);
    }

    #[\Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->inputs);
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     * @return array<string, mixed>[]
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return array_map(fn (StructureInput $input): array => $input->toArray(), $this->inputs);
    }
}
