<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Data\CreateEndpointData;
use App\Models\Eloquent\Endpoint as EndpointModel;
use App\Models\User;
use App\Repositories\EndpointRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EndpointRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EndpointRepository $repository;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EndpointRepository::class);
        $this->user = User::factory()->create();
    }

    public function testItFindsEndpointsByUserIdWithHitCounts(): void
    {
        $otherUser = User::factory()->create();
    
        // Create endpoints for both users
        $endpoint1 = EndpointModel::factory()->create(['user_id' => $this->user->id]);
        $endpoint2 = EndpointModel::factory()->create(['user_id' => $this->user->id]);
        $otherEndpoint = EndpointModel::factory()->create(['user_id' => $otherUser->id]);
    
        // Add hits to endpoint1 (3 hits, 2 unique signatures)
        $endpoint1->hits()->createMany([
            ['signature' => 'abc'],
            ['signature' => 'abc'],
            ['signature' => 'xyz'],
        ]);
    
        // Add hits to endpoint2 (2 hits, 2 unique)
        $endpoint2->hits()->createMany([
            ['signature' => '111'],
            ['signature' => '222'],
        ]);
    
        // Add hits to otherEndpoint (should be ignored)
        $otherEndpoint->hits()->createMany([
            ['signature' => 'aaa'],
            ['signature' => 'bbb'],
        ]);
    
        $results = $this->repository->findByUserId($this->user->id, 10);
    
        static::assertCount(2, $results);
    
        // First endpoint should match either endpoint1 or endpoint2
        $ids = array_map(fn ($e) => $e->id(), $results);
    
        static::assertContains($endpoint1->id, $ids);
        static::assertContains($endpoint2->id, $ids);
    
        // Validate unique/total hits for each
        foreach ($results as $endpoint) {
            if ($endpoint->id() === $endpoint1->id) {
                static::assertSame(2, $endpoint->uniqueHits()); // abc, xyz
                static::assertSame(3, $endpoint->totalHits());
            }
    
            if ($endpoint->id() === $endpoint2->id) {
                static::assertSame(2, $endpoint->uniqueHits()); // 111, 222
                static::assertSame(2, $endpoint->totalHits());
            }
        }
    }

    public function testItCreatesAnEndpoint(): void
    {
        $dto = new CreateEndpointData(
            id: Str::uuid()->toString(),
            userId: $this->user->id,
            path: '/api/test',
            name: 'Test Endpoint',
            inputs: '[{"foo": "bar"}]',
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

    public function testItDeletesEndpointById(): void
    {
        $model = EndpointModel::factory()->create();

        $this->repository->deleteById($model->id);

        static::assertDatabaseMissing('endpoints', ['id' => $model->id]);
    }
}
