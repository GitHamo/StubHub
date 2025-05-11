<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Data\CreateEndpointHitData;

interface EndpointHitRepository
{
    public function create(CreateEndpointHitData $hitDto): void;
}
