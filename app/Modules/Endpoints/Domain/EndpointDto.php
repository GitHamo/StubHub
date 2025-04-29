<?php

declare(strict_types=1);

namespace App\Modules\Endpoints\Domain;

readonly class EndpointDto
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
