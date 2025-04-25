<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Stubs\Infrastructure;

use App\Models\Data\Inputs\Input as StubInput;
use App\Modules\Stubs\Infrastructure\FakerService;
use App\Modules\Stubs\Infrastructure\StubGenerateService;
use App\Models\Data\Stub;
use App\Modules\Stubs\Infrastructure\InputMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StubGenerateServiceTest extends TestCase
{
    private StubGenerateService $service;

    private InputMapper&MockObject $mapper;
    private FakerService&MockObject $faker;


    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new StubGenerateService(
            $this->mapper = $this->createMock(InputMapper::class),
            $this->faker = $this->createMock(FakerService::class),
        );
    }

    public function testUsesComponentsToGenerateOutput(): void
    {
        $rawInputs = [['foo' => 'bar'], [ 'foo' => 'baz' ]];
        $inputs = [
            $inputOne = $this->createMock(StubInput::class),
            $inputTwo = $this->createMock(StubInput::class),
        ];
        $expected = $this->createMock(Stub::class);

        $this->mapper->expects(static::once())
            ->method('mapInputs')
            ->with(static::identicalTo($rawInputs))
            ->willReturn($inputs);
        $this->faker->expects(static::once())
            ->method('generate')
            ->with(
                static::identicalTo($inputOne),
                static::identicalTo($inputTwo),
            )
            ->willReturn($expected);

        $actual = $this->service->generate($rawInputs);

        static::assertSame($expected, $actual);
    }
}
