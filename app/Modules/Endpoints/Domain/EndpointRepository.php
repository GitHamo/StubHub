<?php

declare(strict_types=1);

namespace App\Modules\Endpoints\Domain;

use App\Modules\Endpoints\Domain\EndpointDto;

interface EndpointRepository
{
    /**
     * @return Endpoint[]
     */
    public function findByUserId(int $userId, int $limit): array;

    public function findById(string $id): ?Endpoint;

    public function create(EndpointDto $endpointDto): Endpoint;

    public function deleteById(string $id): void;
}
