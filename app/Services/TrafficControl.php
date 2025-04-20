<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\EndpointNotFoundException;
use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\Hits\Domain\HitDto;
use App\Modules\Hits\Domain\HitRepository;
use App\Modules\StubStorage\StorageRepository;

final readonly class TrafficControl
{
    public function __construct(
        private EndpointRepository $endpointRepository,
        private StorageRepository $storageRepository,
        private HitRepository $hitRepository,
    ) {
    }

    public function serve(string $endpointId, string $signature): string
    {
        $endpoint = $this->endpointRepository->findById($endpointId);

        if ($endpoint === null) {
            return throw new EndpointNotFoundException('Endpoint not found');
        }

        $this->hitRepository->create(new HitDto($endpointId, $signature));

        return $this->storageRepository->fetchById($endpoint->path());
    }
}
