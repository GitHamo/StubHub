<?php

declare(strict_types=1);

namespace App\Models\Data;

/**
 *
 * @phpstan-type OutputValue null|string|int|float|bool|\App\Models\Data\Stub
 */
readonly class StubField
{
    public const string FIELD_KEY = "key";
    public const string FIELD_VALUE = "value";

    /**
     * @param OutputValue|array<array-key, OutputValue> $value
     */
    public function __construct(
        public string $key,
        public null|string|int|float|bool|array|\App\Models\Data\Stub $value,
    ) {
    }

    public function toArray(): array
    {
        return [
            self::FIELD_KEY => $this->key,
            self::FIELD_VALUE => $this->value instanceof Stub ? $this->value->toArray() : $this->value,
        ];
    }
}
