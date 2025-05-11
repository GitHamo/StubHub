<?php

declare(strict_types=1);

namespace App\Models\Data;

abstract class StubInput
{
    public function __construct(
        public string $key,
    ) {
    }
}
