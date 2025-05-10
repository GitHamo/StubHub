<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Modules\Content\Domain\Storage as ContentStorage;
use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Hits\Domain\HitDto as EndpointHitDto;
use App\Repositories\EndpointHitRepository;
use App\Services\TrafficControl;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class TrafficControlTest extends TestCase
{
    private TrafficControl $service;
    private ContentStorage&MockObject $contentStorage;
    private EndpointHitRepository&MockObject $hitRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new TrafficControl(
            $this->contentStorage = $this->createMock(ContentStorage::class),
            $this->hitRepository = $this->createMock(EndpointHitRepository::class),
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

        $this->contentStorage->expects(static::once())
            ->method('get')
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

        $this->contentStorage->expects(static::once())
            ->method('get')
            ->willThrowException(new Exception());
        $this->hitRepository->expects(static::never())
            ->method('create');

        $this->service->serve($this->createMock(Endpoint::class), 'foo');
    }

    public function testGetsResponseOfEndpoint(): void
    {
        $endpointMock = $this->createConfiguredMock(Endpoint::class, [
            'path' => $path = 'baz',
        ]);
        $expected = 'foobarbaz';

        $this->contentStorage->expects(static::once())
            ->method('get')
            ->with(static::identicalTo($path))
            ->willReturn($expected);

        $actual = $this->service->getResponse($endpointMock);

        static::assertSame($expected, $actual);
    }
}
