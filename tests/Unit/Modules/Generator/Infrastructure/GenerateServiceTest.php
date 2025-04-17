<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Generator\Infrastructure;

use App\Modules\Generator\Infrastructure\FakerService;
use App\Modules\Generator\Infrastructure\GenerateService;
use App\Modules\Generator\Infrastructure\InputMapper;
use App\Models\Data\Input;
use App\Models\Data\Stub;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GenerateServiceTest extends TestCase
{
    private GenerateService $service;

    private InputMapper|MockObject $mapper;
    private FakerService|MockObject $faker;


    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new GenerateService(
            $this->mapper = $this->createMock(InputMapper::class),
            $this->faker = $this->createMock(FakerService::class),
        );
    }

    public function testUsesComponentsToGenerateOutput(): void
    {
        $string = 'foo';
        $inputs = [
            $inputOne = $this->createMock(Input::class),
            $inputTwo = $this->createMock(Input::class),
        ];
        $expected = $this->createMock(Stub::class);

        $this->mapper->expects(static::once())
            ->method('map')
            ->with(static::identicalTo($string))
            ->willReturn($inputs);
        $this->faker->expects(static::once())
            ->method('generate')
            ->with(
                static::identicalTo($inputOne),
                static::identicalTo($inputTwo),
            )
            ->willReturn($expected);

        $actual = $this->service->generate($string);

        static::assertSame($expected, $actual);
    }
}
