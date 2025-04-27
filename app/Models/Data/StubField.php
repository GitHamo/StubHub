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
}
