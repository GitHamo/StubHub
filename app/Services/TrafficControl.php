<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\EndpointNotFoundException;
use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\StubStorage\StorageRepository;

final readonly class TrafficControl
{
    public function __construct(
        private EndpointRepository $endpointRepository,
        private StorageRepository $storageRepository,
    ) {
    }

    public function request(string $endpointId): string
    {
        $endpoint = $this->endpointRepository->findById($endpointId);

        if ($endpoint === null) {
            return throw new EndpointNotFoundException('Endpoint not found');
        }

        return $this->storageRepository->fetchById($endpoint->path());
    }
}
