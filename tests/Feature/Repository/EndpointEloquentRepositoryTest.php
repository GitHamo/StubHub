<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Modules\Endpoints\Domain\EndpointDto;
use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint as EndpointModel;
use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\EndpointEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EndpointEloquentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EndpointEloquentRepository $repository;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EndpointEloquentRepository();
        $this->user = User::factory()->create();
    }

    public function testItCreatesAnEndpoint(): void
    {
        $dto = new EndpointDto(
            id: Str::uuid()->toString(),
            userId: $this->user->id,
            path: '/api/test',
            name: 'Test Endpoint'
        );

        $entity = $this->repository->create($dto);

        static::assertDatabaseHas('endpoints', [
            'id' => $dto->id,
            'user_id' => $dto->userId,
            'path' => $dto->path,
            'name' => $dto->name,
        ]);

        static::assertSame($dto->id, $entity->id());
        static::assertSame($dto->userId, $entity->userId());
        static::assertSame($dto->path, $entity->path());
        static::assertSame($dto->name, $entity->name());
    }

    public function testItFindsEndpointById(): void
    {
        $model = EndpointModel::factory()->create();

        $entity = $this->repository->findById($model->id);

        static::assertNotNull($entity);
        static::assertSame($model->id, $entity->id());
        static::assertSame($model->user_id, $entity->userId());
        static::assertSame($model->path, $entity->path());
        static::assertSame($model->name, $entity->name());
    }

    public function testItReturnsNullIfEndpointNotFound(): void
    {
        $entity = $this->repository->findById(Str::uuid()->toString());

        static::assertNull($entity);
    }
}
