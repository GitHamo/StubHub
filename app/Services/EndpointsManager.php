<?php

declare(strict_types=1);

namespace App\Services;

use App\Modules\Stubs\StubGenerator;
use App\Modules\Endpoints\Domain\EndpointDto;
use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\StubStorage\StorageRepository;

final readonly class EndpointsManager
{
    public function __construct(
        private StubGenerator $stubGenerator,
        private EndpointRepository $endpointRepository,
        private StorageRepository $storageRepository,
    ) {
    }

    /**
     * @param array<array-key, array<string, mixed>> $inputsData
     */
    public function createEndpoint(string $uuid, int $userId, string $name, array $inputsData): Endpoint
    {
        $stub = $this->stubGenerator->generate($inputsData);
        $path = $this->storageRepository->create($uuid, $stub);

        return $this->endpointRepository->create(new EndpointDto($uuid, $userId, $path, $name));
    }
}
