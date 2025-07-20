<?php

declare(strict_types=1);

namespace Tests\Feature\Repository;

use App\Models\Data\SaveStubContentData;
use App\Models\Domain\Stub;
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
        $stub = $this->createConfiguredMock(Stub::class, [
            'jsonSerialize' => ['foo' => 'bar'],
        ]);

        $content = '{"foo":"bar"}';

        $dto = new SaveStubContentData(
            name: $filename = 'example.json',
            stub: $stub,
        );

        $entity = $this->repository->create($dto);

        static::assertDatabaseHas('stub_contents', [
            'filename' => $filename,
            'content' => $content,
        ]);

        static::assertSame($filename, $entity->name());
        static::assertSame($content, $entity->content());
    }

    public function testItUpdatesStubContent(): void
    {
        $filename = 'toupdate.json';
        $initialContent = '{"status":"initial"}';
        $updatedContent = '{"status":"updated"}';

        StubContentModel::create([
            'filename' => $filename,
            'content' => $initialContent,
        ]);

        $stub = $this->createConfiguredMock(Stub::class, [
            'jsonSerialize' => ['status' => 'updated'],
        ]);

        $dto = new \App\Models\Data\SaveStubContentData(
            name: $filename,
            stub: $stub,
        );

        $this->repository->update($dto);

        static::assertDatabaseHas('stub_contents', [
            'filename' => $filename,
            'content' => $updatedContent,
        ]);

        static::assertDatabaseMissing('stub_contents', [
            'filename' => $filename,
            'content' => $initialContent,
        ]);
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
