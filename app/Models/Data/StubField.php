<?php

declare(strict_types=1);

namespace App\Models\Data;

readonly class StubField
{
    public function __construct(
        public string $key,
        public mixed $value,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            $this->key => $this->value instanceof Stub ? $this->value->toArray() : $this->value,
        ];
    }
}
