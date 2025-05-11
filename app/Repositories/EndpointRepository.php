<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Endpoints\Domain\EndpointDto;

interface EndpointRepository
{
    /**
     * @return Endpoint[]
     */
    public function findByUserId(int $userId, int $limit): array;

    public function create(EndpointDto $endpointDto): Endpoint;

    public function deleteById(string $id): void;
}
