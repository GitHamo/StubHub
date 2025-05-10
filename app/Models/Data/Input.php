<?php

declare(strict_types=1);

namespace App\Models\Data;

abstract class Input
{
    public function __construct(
        public string $key,
    ) {
    }
}
