<?php

declare(strict_types=1);

namespace App\Models\Data;

/**
 *
 * @phpstan-type OutputValue null|string|int|float|bool|\App\Models\Data\Stub
 */
readonly class StubField
{
    /**
     * @param OutputValue|array<array-key, OutputValue> $value
     */
    public function __construct(
        public string $key,
        public null|string|int|float|bool|array|\App\Models\Data\Stub $value,
    ) {
    }

    /**
     * @return array<string, array<string, mixed>|bool|float|int|string|null>
     */
    public function toArray(): array
    {
        return [
            $this->key => $this->value instanceof Stub ? $this->value->toArray() : $this->value,
        ];
    }
}
