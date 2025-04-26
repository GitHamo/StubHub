<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Data\Stub;
use App\Services\EndpointsManager;
use App\Modules\Stubs\StubGenerator;
use App\Modules\Endpoints\Domain\EndpointDto;
use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\StubStorage\StorageRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class EndpointsManagerTest extends TestCase
{
    private StubGenerator&MockObject $stubGenerator;
    private EndpointRepository&MockObject $endpointRepository;
    private StorageRepository&MockObject $storageRepository;
    private EndpointsManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stubGenerator = $this->createMock(StubGenerator::class);
        $this->endpointRepository = $this->createMock(EndpointRepository::class);
        $this->storageRepository = $this->createMock(StorageRepository::class);

        $this->manager = new EndpointsManager(
            stubGenerator: $this->stubGenerator,
            endpointRepository: $this->endpointRepository,
            storageRepository: $this->storageRepository,
        );
    }

    public function testUsesComponentsToCreateEndpoint(): void
    {
        $uuid = 'foo';
        $userId = 123;
        $name = 'Test Endpoint';
        $path = 'foobarbaz';
        $inputs = [
            ['foo' => 'bar'],
            ['foo' => 'baz'],
        ];

        $stubMock = $this->createMock(Stub::class);
        $endpointDto = new EndpointDto($uuid, $userId, $name, $path);
        $expected = $this->createMock(Endpoint::class);

        $this->stubGenerator
            ->expects(self::once())
            ->method('generate')
            ->with(static::identicalTo($inputs))
            ->willReturn($stubMock);

        $this->storageRepository
            ->expects(self::once())
            ->method('create')
            ->with(static::identicalTo($path), static::identicalTo($stubMock));

        $this->endpointRepository
            ->expects(self::once())
            ->method('create')
            ->with(static::equalTo($endpointDto))
            ->willReturn($expected);

        $actual = $this->manager->createEndpoint($uuid, $userId, $name, $path, $inputs);

        static::assertSame($expected, $actual);
    }

    public function testUsesComponentsToGetEndpointListWithLimit(): void
    {
        $limit = mt_rand();
        $userId = 123;
        $expected = [
            $this->createMock(Endpoint::class),
            $this->createMock(Endpoint::class),
            $this->createMock(Endpoint::class),
        ];

        $this->endpointRepository
            ->expects(self::once())
            ->method('findByUserId')
            ->with(static::equalTo($userId), static::equalTo($limit))
            ->willReturn($expected);

        $actual = $this->manager->getEndpointList($userId, $limit);

        static::assertSame($expected, $actual);
        static::assertCount(3, $actual);
    }

    public function testUsesComponentsToDeleteEndpoint(): void
    {
        $id = 'foo';
        $path = 'bar';

        $this->endpointRepository
            ->expects(self::once())
            ->method('deleteById')
            ->with(static::identicalTo($id));
        $this->storageRepository
            ->expects(self::once())
            ->method('delete')
            ->with(static::identicalTo($path));

        $this->manager->deleteEndpoint($id, $path);
    }
}
