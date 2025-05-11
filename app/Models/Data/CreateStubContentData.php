<?php

declare(strict_types=1);

namespace App\Models\Data;

readonly class CreateStubContentData
{
    public function __construct(
        public string $name,
        public string $content,
    ) {
    }
}
