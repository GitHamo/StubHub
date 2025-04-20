<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Enums\StubFieldContext;

readonly class StubInput
{
    public function __construct(
        public string $key,
        public StubFieldContext $context,
    ) {
    }
}
