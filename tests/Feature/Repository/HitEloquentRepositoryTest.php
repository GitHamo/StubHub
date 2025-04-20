<?php

declare(strict_types=1);

namespace Tests\Feature\Repository;

use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint as EndpointModel;
use App\Modules\Hits\Domain\HitDto as EndpointHitDto;
use App\Modules\Hits\Infrastructure\Persistence\Eloquent\HitEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class HitEloquentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private HitEloquentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new HitEloquentRepository();
    }

    public function testItCreatesEndpointHit(): void
    {
        EndpointModel::factory()->create(['id' => $endpointId = Str::uuid()->toString()]);

        $dto = new EndpointHitDto($endpointId, 'bar');

        $this->repository->create($dto);

        static::assertDatabaseHas('endpoint_hits', [
            'endpoint_id' => $dto->endpointId,
            'signature' => $dto->signature,
        ]);
    }
}
