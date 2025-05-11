<?php

declare(strict_types=1);

namespace App\Models\Data;

readonly class CreateEndpointData
{
    public function __construct(
        public string $id,
        public int $userId,
        public string $name,
        public string $path,
        public string $inputs,
    ) {
    }
}
