<?php

declare(strict_types=1);

namespace App\Models\Data;

readonly class CreateEndpointHitData
{
    public function __construct(
        public string $endpointId,
        public string $signature,
    ) {
    }
}
