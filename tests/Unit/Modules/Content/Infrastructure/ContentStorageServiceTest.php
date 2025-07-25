<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Content\Infrastructure;

use App\Models\Data\SaveStubContentData;
use App\Models\Domain\Stub;
use App\Models\Domain\StubContent;
use App\Modules\Content\Infrastructure\ContentStorageService;
use App\Modules\Content\Infrastructure\EncryptionHelper;
use App\Repositories\StubContentRepository;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ContentStorageServiceTest extends TestCase
{
    private ContentStorageService $service;
    private StubContentRepository&MockObject $repository;
    private EncryptionHelper&MockObject $encryptionHelper;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ContentStorageService(
            $this->repository = $this->createMock(StubContentRepository::class),
            $this->encryptionHelper = $this->createMock(EncryptionHelper::class),
        );
    }

    public function testCreatesContentAndReturnsPath(): void
    {
        $stub = $this->createMock(Stub::class);
        $stub->method('jsonSerialize')->willReturn(['foo' => 'bar']);

        $path = 'abcdef';
        $stubName = 'hashed-abcdef';

        $this->encryptionHelper->method('random')->with(20)->willReturn($path);
        $this->encryptionHelper->method('hash')->with($path)->willReturn($stubName);

        $this->repository->expects($this->once())
            ->method('create')
            ->with(
                static::equalTo(new SaveStubContentData($stubName, $stub)),
            );

        $expected = $path;
        $actual = $this->service->create($stub);

        static::assertSame($expected, $actual);
    }

    public function testUpdatesContent(): void
    {
        $stub = $this->createMock(Stub::class);
        $stub->method('jsonSerialize')->willReturn(['foo' => 'bar']);

        $path = 'abcdef';
        $stubName = 'hashed-abcdef';

        $this->encryptionHelper->method('hash')->with($path)->willReturn($stubName);

        $this->repository->expects($this->once())
            ->method('update')
            ->with(
                static::equalTo(new SaveStubContentData($stubName, $stub)),
            );

        $this->service->update($path, $stub);
    }

    public function testGetsContentByHashedPath(): void
    {
        $path = 'xyz123';
        $stubName = 'hashed-xyz123';

        $stubContent = $this->createMock(StubContent::class);
        $stubContent->method('content')->willReturn('{"some":"content"}');

        $this->encryptionHelper->method('hash')->with($path)->willReturn($stubName);
        $this->repository->method('find')->with($stubName)->willReturn($stubContent);

        $expected = '{"some":"content"}';
        $actual = $this->service->get($path);

        static::assertSame($expected, $actual);
    }

    public function testDeletesStubByHashedPath(): void
    {
        $path = 'delete-me';
        $stubName = 'hashed-delete-me';

        $this->encryptionHelper->method('hash')->with($path)->willReturn($stubName);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($stubName);

        $this->service->delete($path);

        $this->addToAssertionCount(1); // no exception = pass
    }

    public function testGetThrowsWhenPathIsEmpty(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Path must not be empty.');

        $this->service->get('   ');
    }

    public function testDeleteThrowsWhenPathIsEmpty(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Path must not be empty.');

        $this->service->delete('');
    }
}
