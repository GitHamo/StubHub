<?php

declare(strict_types=1);

namespace App\Modules\Structure\Domain;

use App\Models\Data\Input;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @implements \IteratorAggregate<int, \App\Models\Data\Input>
 */
readonly class Structure implements IteratorAggregate, JsonSerializable
{
    /**
     * @param \App\Models\Data\Input[] $inputs
     */
    private function __construct(
        private array $inputs,
    ) {
    }

    public static function create(Input ...$inputs): self
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
        return array_map(fn (Input $input): array => $input->toArray(), $this->inputs);
    }
}
