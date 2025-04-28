<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\StubGenerate\Infrastructure;

use App\Models\Data\Inputs\Input as StubInput;
use App\Models\Data\Stub;
use App\Modules\StubGenerate\Infrastructure\FakerStubMapper;
use App\Modules\StubGenerate\Infrastructure\StubGenerateService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StubGenerateServiceTest extends TestCase
{
    private StubGenerateService $service;

    private FakerStubMapper&MockObject $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new StubGenerateService(
            $this->mapper = $this->createMock(FakerStubMapper::class),
        );
    }

    public function testUsesComponentsToGenerateOutput(): void
    {
        $inputs = [
            $inputOne = $this->createMock(StubInput::class),
            $inputTwo = $this->createMock(StubInput::class),
        ];
        $expected = $this->createMock(Stub::class);

        $this->mapper->expects(static::once())
            ->method('parseInputs')
            ->with(static::identicalTo($inputOne), static::identicalTo($inputTwo))
            ->willReturn($expected);

        $actual = $this->service->generate(...$inputs);

        static::assertSame($expected, $actual);
    }
}
