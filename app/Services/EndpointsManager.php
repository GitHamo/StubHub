<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Modules\Constraints\Domain\ConstraintsCheck;
use App\Modules\Endpoints\Domain\EndpointDto;
use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\StubGenerate\StubGenerator;
use App\Modules\StubStorage\StorageRepository;
use App\Support\InputMapper;

final readonly class EndpointsManager
{
    private const int PATH_LENGTH = 20;

    public function __construct(
        private InputMapper $inputMapper,
        private ConstraintsCheck $constraintsCheck,
        private StubGenerator $stubGenerator,
        private EndpointRepository $endpointRepository,
        private StorageRepository $storageRepository,
    ) {
    }

    /**
     * @param list<array<string, mixed>> $inputsData
     */
    public function createEndpoint(string $uuid, User $user, string $name, array $inputsData): Endpoint
    {
        $inputs = $this->inputMapper->mapInputs($inputsData);
        $this->constraintsCheck->ensureUserCanCreateEndpoint($user);

        $this->constraintsCheck->ensureInputRepeatWithinLimit($user, ...$inputs);

        $stub = $this->stubGenerator->generate(...$inputs);

        $this->constraintsCheck->ensureStubSizeWithinLimits($user, $stub);

        $path = $this->generatePath();

        $this->storageRepository->create($path, $stub);

        $dto = new EndpointDto($uuid, $user->id, $name, $path, json_encode($inputs, JSON_THROW_ON_ERROR));

        return $this->endpointRepository->create($dto);
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

    private function generatePath(): string
    {
        return bin2hex(random_bytes(self::PATH_LENGTH));
    }
}
