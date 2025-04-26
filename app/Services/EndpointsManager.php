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
     * @param list<array<string, mixed>> $inputsData
     */
    public function createEndpoint(string $uuid, int $userId, string $name, string $path, array $inputsData): Endpoint
    {
        $stub = $this->stubGenerator->generate($inputsData);

        $this->storageRepository->create($path, $stub);

        return $this->endpointRepository->create(new EndpointDto($uuid, $userId, $name, $path));
    }

    /**
     * @return Endpoint[]
     */
    public function getEndpointList(int $userId, int $limit): array
    {
        return $this->endpointRepository->findByUserId($userId, $limit);
    }

    public function deleteEndpoint(string $uuid, string $path): void
    {
        $this->endpointRepository->deleteById($uuid);
        $this->storageRepository->delete($path);
    }
}
