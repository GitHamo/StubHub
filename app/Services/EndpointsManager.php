<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Data\CreateEndpointData;
use App\Models\Domain\Endpoint;
use App\Models\User;
use App\Modules\Constraints\Domain\ConstraintsCheck;
use App\Modules\Content\Domain\Generator as ContentGenerator;
use App\Modules\Content\Domain\Storage as ContentStorage;
use App\Modules\Structure\Domain\InputMapper;
use App\Repositories\EndpointRepository;

final readonly class EndpointsManager
{
    public function __construct(
        private InputMapper $inputMapper,
        private ConstraintsCheck $constraintsCheck,
        private ContentGenerator $contentGenerator,
        private ContentStorage $contentStorage,
        private EndpointRepository $endpointRepository,
    ) {
    }

    /**
     * @param list<array<string, mixed>> $inputsData
     */
    public function createEndpoint(string $uuid, User $user, string $name, array $inputsData): Endpoint
    {
        $inputs = $this->inputMapper->map($inputsData);

        $this->constraintsCheck->ensureUserCanCreateEndpoint($user);

        $this->constraintsCheck->ensureInputRepeatWithinLimit($user, ...$inputs);

        $stub = $this->contentGenerator->generate(...$inputs);

        $this->constraintsCheck->ensureStubSizeWithinLimits($user, $stub);

        $path = $this->contentStorage->create($stub);

        $dto = new CreateEndpointData($uuid, $user->id, $name, $path, $inputs->toJson());

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
        $this->contentStorage->delete($path);
    }
}
