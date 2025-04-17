<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Models\ContextEnum;

readonly class Input
{
    public function __construct(
        public string $key,
        public ContextEnum $context,
    ) {
    }
}
