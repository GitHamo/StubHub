<?php

declare(strict_types=1);

namespace Tests\Feature\Repository;

use App\Models\Endpoint as EndpointModel;
use App\Modules\Hits\Domain\HitDto as EndpointHitDto;
use App\Repositories\EndpointHitRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EndpointHitRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EndpointHitRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EndpointHitRepository::class);
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
