<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\StubGenerate\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\Inputs\Single;
use App\Models\Data\Stub;
use App\Models\Data\StubField;
use App\Modules\StubGenerate\Infrastructure\FakerStubMapper;
use Faker\Generator;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FakerStubMapperTest extends TestCase
{
    private Generator&MockObject $faker;

    protected function setUp(): void
    {
        $this->faker = $this->createMock(Generator::class);
    }

    public function testCallsFakerMethod(): void
    {
        $context = $this->getRandomContext();
        $faker = $this->createConfiguredMock(Generator::class, [
            $methodName = 'mimeType' => $methodResponse = 'bar',
        ]);

        $input = new Single($key = 'baz', $context);
        $mapper = new FakerStubMapper($faker, [$context->value => [$methodName]]);

        $expected = new Stub([
            new StubField($key, $methodResponse),
        ]);

        $faker->expects(static::once())
            ->method($methodName)
            ->willReturn($methodResponse);

        $actual = $mapper->parseInputs($input);

        static::assertEquals($expected, $actual);
    }

    public function testThrowsExceptionInCaseOfInvalidContext(): void
    {
        $context = $this->getRandomContext();
        $contextsMap = [
            'bar' => ['baz'],
        ];

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage("Unknown context: {$context->value}");

        $input = new Single('foo', $context);
        $mapper = new FakerStubMapper($this->faker, $contextsMap);

        $mapper->parseInputs($input);
    }

    public function testThrowsExceptionForInvalidMethodType(): void
    {
        $context = $this->getRandomContext();
        $contextsMap = [
            $context->value => [123], // invalid method type (not string)
        ];

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage("Invalid method type (not string) for context: {$context->value}");

        $input = new Single('foo', $context);
        $mapper = new FakerStubMapper($this->faker, $contextsMap);

        $mapper->parseInputs($input);
    }

    private function getRandomContext(): StubFieldContext|array
    {
        return StubFieldContext::cases()[array_rand(StubFieldContext::cases())];
    }
}
