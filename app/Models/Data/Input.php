<?php

declare(strict_types=1);

namespace App\Models\Data;

use JsonSerializable;

abstract class Input implements JsonSerializable
{
    public function __construct(
        public string $key,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
        ];
    }
}
