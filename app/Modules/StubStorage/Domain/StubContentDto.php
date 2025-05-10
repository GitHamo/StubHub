<?php

declare(strict_types=1);

namespace App\Modules\StubStorage\Domain;

readonly class StubContentDto
{
    public function __construct(
        public string $name,
        public string $content,
    ) {
    }
}
