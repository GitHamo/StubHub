<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Api\Data\Serializable\Arrayable;
use App\Support\StrictJson;
use JsonSerializable;

readonly class StubField implements Arrayable
{
    public function __construct(
        public string $key,
        public mixed $value,
    ) {
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->getValue($this->value),
        ];
    }

    private function getValue(mixed $value): mixed
    {
        return match(true) {
            $value instanceof JsonSerializable => StrictJson::encode($value),
            $value instanceof StubField => $value->toArray(),
            is_array($value) && array_is_list($value) => array_map([$this, 'getValue'], $value),
            is_object($value) && method_exists($value, 'toArray') => $value->toArray(),
            default => $value,
        };
    }
}
