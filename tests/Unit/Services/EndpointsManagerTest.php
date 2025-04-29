<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Data\Inputs\Input;
use App\Models\Data\Stub;
use App\Models\User;
use App\Modules\Constraints\Domain\ConstraintsCheck;
use App\Services\EndpointsManager;
use App\Modules\Endpoints\Domain\EndpointDto;
use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\StubGenerate\StubGenerator;
use App\Modules\StubStorage\StorageRepository;
use App\Support\InputMapper;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class EndpointsManagerTest extends TestCase
{
    private InputMapper&MockObject $inputMapper;
    private ConstraintsCheck&MockObject $constraintsCheck;
    private StubGenerator&MockObject $stubGenerator;
    private EndpointRepository&MockObject $endpointRepository;
    private StorageRepository&MockObject $storageRepository;
    private EndpointsManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inputMapper = $this->createMock(InputMapper::class);
        $this->constraintsCheck = $this->createMock(ConstraintsCheck::class);
        $this->stubGenerator = $this->createMock(StubGenerator::class);
        $this->endpointRepository = $this->createMock(EndpointRepository::class);
        $this->storageRepository = $this->createMock(StorageRepository::class);

        $this->manager = new EndpointsManager(
            stubGenerator: $this->stubGenerator,
            endpointRepository: $this->endpointRepository,
            storageRepository: $this->storageRepository,
            constraintsCheck: $this->constraintsCheck,
            inputMapper: $this->inputMapper,
        );
    }

    public function testUsesComponentsToCreateEndpoint(): void
    {
        $uuid = 'foo';
        $user = User::factory()->make(['id' => $userId = 123]);
        $name = 'Test Endpoint';
        $inputsData = [
            ['foo' => 'bar'],
            ['foo' => 'baz'],
        ];
        $inputs = [
            $this->createMock(Input::class),
        ];

        // $capturedPath = null;

        $stubMock = $this->createMock(Stub::class);
        $expected = $this->createMock(Endpoint::class);

        $this->inputMapper->expects(self::once())
            ->method('mapInputs')
            ->with(static::identicalTo($inputsData))
            ->willReturn($inputs);

        $this->constraintsCheck->expects(self::once())
            ->method('ensureUserCanCreateEndpoint')
            ->with(static::identicalTo($user));

        $this->constraintsCheck->expects(self::once())
            ->method('ensureInputRepeatWithinLimit')
            ->with(
                static::identicalTo($user),
                ...$inputs,
            );

        $this->stubGenerator
            ->expects(self::once())
            ->method('generate')
            ->with(...$inputs)
            ->willReturn($stubMock);

        $this->constraintsCheck->expects(self::once())
            ->method('ensureStubSizeWithinLimits')
            ->with(static::identicalTo($user), static::identicalTo($stubMock));

        $this->storageRepository
            ->expects(self::once())
            ->method('create')
            ->with(
                static::callback(function ($path) use (&$capturedPath): bool {
                    $capturedPath = $path; // set generated path for later usage and validate it

                    return is_string($path) && preg_match('/^[a-zA-Z0-9]{40}$/', $path); // Ensures 40-character alphanumeric string
                }),
                static::identicalTo($stubMock),
            );

        $this->endpointRepository
            ->expects(self::once())
            ->method('create')
            // ->with(static::callback(
            //     fn ($dto): bool =>
            //     $dto instanceof EndpointDto
            //         && $dto->id === $uuid
            //         && $dto->userId === $userId
            //         && $dto->name === $name
            //         && $dto->path === $capturedPath
            //         && $dto->inputs === json_encode($inputs, JSON_THROW_ON_ERROR)
            // ))
            ->willReturn($expected);

        $actual = $this->manager->createEndpoint($uuid, $user, $name, $inputsData);

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
