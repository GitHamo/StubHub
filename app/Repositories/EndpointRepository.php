<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Data\CreateEndpointData;
use App\Models\Domain\Endpoint;

interface EndpointRepository
{
    /**
     * @return Endpoint[]
     */
    public function findByUserId(int $userId, int $limit): array;

    public function create(CreateEndpointData $endpointDto): Endpoint;

    public function deleteById(string $id): void;
}
