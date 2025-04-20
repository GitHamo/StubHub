<?php

declare(strict_types=1);

namespace App\Modules\Hits\Domain;

readonly class HitDto
{
    public function __construct(
        public string $endpointId,
        public string $signature,
    ) {
    }
}
