<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Hits\Domain\HitDto as EndpointHitDto;
use App\Modules\Hits\Domain\HitRepository;
use App\Modules\StubStorage\StorageRepository;
use App\Services\TrafficControl;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class TrafficControlTest extends TestCase
{
    private TrafficControl $service;
    private StorageRepository&MockObject $storageRepository;
    private HitRepository&MockObject $hitRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new TrafficControl(
            $this->storageRepository = $this->createMock(StorageRepository::class),
            $this->hitRepository = $this->createMock(HitRepository::class),
        );
    }

    public function testServesRequest(): void
    {
        $signature = 'foo';
        $endpointMock = $this->createConfiguredMock(Endpoint::class, [
            'id' => $id = 'bar',
            'path' => $path = 'baz',
        ]);
        $endpointHitDto = new EndpointHitDto($id, $signature);
        $expected = 'foobarbaz';

        $this->storageRepository->expects(static::once())
            ->method('fetchById')
            ->with(static::identicalTo($path))
            ->willReturn($expected);
        $this->hitRepository->expects(static::once())
            ->method('create')
            ->with(static::equalTo($endpointHitDto));

        $actual = $this->service->serve($endpointMock, $signature);

        static::assertSame($expected, $actual);
    }

    public function testDoesNotCollectHitWhenErrorWithStorage(): void
    {
        static::expectException(Exception::class);

        $this->storageRepository->expects(static::once())
            ->method('fetchById')
            ->willThrowException(new Exception());
        $this->hitRepository->expects(static::never())
            ->method('create');

        $this->service->serve($this->createMock(Endpoint::class), 'foo');
    }
}
