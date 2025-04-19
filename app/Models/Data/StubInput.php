<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Enums\SubFieldContextEnum;

readonly class StubInput
{
    public function __construct(
        public string $key,
        public SubFieldContextEnum $context,
    ) {
    }
}
