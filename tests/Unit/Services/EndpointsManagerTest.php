<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Data\CreateEndpointData;
use App\Models\Data\Input;
use App\Models\Domain\Endpoint;
use App\Models\Domain\Stub;
use App\Models\User;
use App\Modules\Constraints\Domain\ConstraintsCheck;
use App\Modules\Content\Domain\StubGenerator as ContentGenerator;
use App\Modules\Content\Domain\StubStorage as ContentStorage;
use App\Modules\Structure\Domain\InputMapper;
use App\Modules\Structure\Domain\Structure;
use App\Repositories\EndpointRepository;
use App\Services\EndpointsManager;
use ArrayIterator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class EndpointsManagerTest extends TestCase
{
    private InputMapper&MockObject $inputMapper;
    private ConstraintsCheck&MockObject $constraintsCheck;
    private ContentGenerator&MockObject $contentGenerator;
    private ContentStorage&MockObject $contentStorage;
    private EndpointRepository&MockObject $endpointRepository;
    private EndpointsManager $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new EndpointsManager(
            $this->inputMapper = $this->createMock(InputMapper::class),
            $this->constraintsCheck = $this->createMock(ConstraintsCheck::class),
            $this->contentGenerator = $this->createMock(ContentGenerator::class),
            $this->contentStorage = $this->createMock(ContentStorage::class),
            $this->endpointRepository = $this->createMock(EndpointRepository::class),
        );
    }

    public function testUsesComponentsToCreateEndpoint(): void
    {
        $uuid = 'foo';
        $user = User::factory()->make(['id' => $userId = 123]);
        $name = 'Test Endpoint';
        $inputsData = [['foo' => 'bar']];
        $structureMock = $this->createConfiguredMock(Structure::class, [
            'getIterator' => new ArrayIterator($inputs = [
                $this->createMock(Input::class),
                $this->createMock(Input::class),
            ]),
        ]);
        $stubMock = $this->createMock(Stub::class);
        $expected = $this->createMock(Endpoint::class);

        $this->inputMapper->expects(self::once())
            ->method('map')
            ->with(static::identicalTo($inputsData))
            ->willReturn($structureMock);

        $this->constraintsCheck->expects(self::once())
            ->method('ensureUserCanCreateEndpoint')
            ->with(static::identicalTo($user));

        $this->constraintsCheck->expects(self::once())
            ->method('ensureInputRepeatWithinLimit')
            ->with(
                static::identicalTo($user),
                ...$inputs,
            );

        $this->constraintsCheck->expects(self::once())
            ->method('ensureInputDepthWithinLimit')
            ->with(
                static::identicalTo($user),
                ...$inputs,
            );

        $this->contentGenerator
            ->expects(self::once())
            ->method('generate')
            ->with(...$inputs)
            ->willReturn($stubMock);

        $this->constraintsCheck->expects(self::once())
            ->method('ensureStubSizeWithinLimits')
            ->with(static::identicalTo($user), static::identicalTo($stubMock));

        $this->contentStorage
            ->expects(self::once())
            ->method('create')
            ->with(
                static::identicalTo($stubMock),
            )
            ->willReturn($path = 'bar');

        $this->endpointRepository
            ->expects(self::once())
            ->method('create')
            ->with(
                static::equalTo(
                    new CreateEndpointData(
                        id: $uuid,
                        userId: $userId,
                        path: $path,
                        name: $name,
                        inputs: $structureMock,
                    ),
                ),
            )
            ->willReturn($expected);

        $actual = $this->service->createEndpoint($uuid, $user, $name, $inputsData);

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

        $actual = $this->service->getEndpointList($userId, $limit);

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
        $this->contentStorage
            ->expects(self::once())
            ->method('delete')
            ->with(static::identicalTo($path));

        $this->service->deleteEndpoint($id, $path);
    }
}
