<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Modules\Hits\Domain\HitDto as EndpointHitDto;

interface EndpointHitRepository
{
    public function create(EndpointHitDto $hitDto): void;
}
