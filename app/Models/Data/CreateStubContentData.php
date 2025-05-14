<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Models\Domain\Stub;

readonly class CreateStubContentData
{
    public function __construct(
        public string $name,
        public Stub $stub,
    ) {
    }
}
