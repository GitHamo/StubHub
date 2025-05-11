<?php

declare(strict_types=1);

namespace Tests\Feature\Repository;

use App\Models\Data\CreateStubContentData;
use App\Models\Eloquent\StubContent as StubContentModel;
use App\Repositories\StubContentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StubContentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private StubContentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(StubContentRepository::class);
    }

    public function testItCreatesStubContent(): void
    {
        $dto = new CreateStubContentData(
            name: $filename = 'example.json',
            content: $content = '{"key":"value"}'
        );

        $entity = $this->repository->create($dto);

        static::assertDatabaseHas('stub_contents', [
            'filename' => $filename,
            'content' => $content,
        ]);

        static::assertSame($filename, $entity->name());
        static::assertSame($content, $entity->content());
    }

    public function testItFindsStubContent(): void
    {
        $filename = 'found.json';
        $content = '{"foo":"bar"}';

        StubContentModel::create([
            'filename' => $filename,
            'content' => $content,
        ]);

        $entity = $this->repository->find($filename);

        static::assertSame($filename, $entity->name());
        static::assertSame($content, $entity->content());
    }

    public function testItThrowsWhenStubContentNotFound(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->find('non-existing.json');
    }

    public function testItDeletesStubContent(): void
    {
        $filename = 'todelete.json';

        StubContentModel::create([
            'filename' => $filename,
            'content' => '{"delete":"me"}',
        ]);

        $this->repository->delete($filename);

        static::assertDatabaseMissing('stub_contents', [
            'filename' => $filename,
        ]);
    }
}
