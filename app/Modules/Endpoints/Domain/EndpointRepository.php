<?php

declare(strict_types=1);

namespace App\Modules\Endpoints\Domain;

use App\Modules\Endpoints\Domain\EndpointDto;

interface EndpointRepository
{
    public function findById(string $id): ?Endpoint;

    public function create(EndpointDto $endpointDto): Endpoint;
}
