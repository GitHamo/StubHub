<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\EndpointNotFoundException;
use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Endpoints\Domain\EndpointDto;
use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\Hits\Domain\HitDto as EndpointHitDto;
use App\Modules\Hits\Domain\HitRepository;
use App\Modules\StubStorage\StorageRepository;
use App\Services\TrafficControl;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class TrafficControlTest extends TestCase
{
    private TrafficControl $service;
    private EndpointRepository&MockObject $endpointRepository;
    private StorageRepository&MockObject $storageRepository;
    private HitRepository&MockObject $hitRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new TrafficControl(
            $this->endpointRepository = $this->createMock(EndpointRepository::class),
            $this->storageRepository = $this->createMock(StorageRepository::class),
            $this->hitRepository = $this->createMock(HitRepository::class),
        );
    }

    public function testServesRequest(): void
    {
        $id = 'foo';
        $signature = 'bar';
        $endpointMock = $this->createConfiguredMock(Endpoint::class, [
            'path' => $path = 'bar',
        ]);
        $endpointHitDto = new EndpointHitDto($id, $signature);
        $expected = 'baz';

        $this->endpointRepository->expects(static::once())
            ->method('findById')
            ->with(static::identicalTo($id))
            ->willReturn($endpointMock);
        $this->storageRepository->expects(static::once())
            ->method('fetchById')
            ->with(static::identicalTo($path))
            ->willReturn($expected);
        $this->hitRepository->expects(static::once())
            ->method('create')
            ->with(static::equalTo($endpointHitDto));

        $actual = $this->service->serve($id, $signature);

        static::assertSame($expected, $actual);
    }

    public function testThrowsExceptionInCaseOfNonExistingEndpoint(): void
    {
        $this->endpointRepository->expects(static::once())
            ->method('findById')
            ->willReturn(null);

        static::expectException(EndpointNotFoundException::class);
        static::expectExceptionMessage('Endpoint not found');

        $this->service->serve('foo', 'bar');
    }
}
