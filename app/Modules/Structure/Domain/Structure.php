<?php

declare(strict_types=1);

namespace App\Modules\Structure\Domain;

use App\Models\Data\Input;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

class Structure implements IteratorAggregate, JsonSerializable
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

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->inputs);
    }

    public function jsonSerialize(): array
    {
        return array_map('get_object_vars', $this->inputs);
    }

    public function toJson(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }
}
