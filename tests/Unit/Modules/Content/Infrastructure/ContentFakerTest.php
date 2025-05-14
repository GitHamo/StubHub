<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Content\Infrastructure;

use App\Enums\StubFieldContext;
use App\Modules\Content\Infrastructure\ContentFaker;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class ContentFakerTest extends TestCase
{
    public function testItFakes(): void
    {
        $context = StubFieldContext::cases()[random_int(0, count(StubFieldContext::cases()) - 1)];
        $method = 'foo';
        $expected = 'bar';

        $faker = $this->createMock(Generator::class);
        $faker->expects(static::once())
            ->method('__call')
            ->with(static::identicalTo($method))
            ->willReturn($expected);

        $service = new ContentFaker($faker, [
            $context->value => [$method],
        ]);

        static::assertSame($expected, $service->fake($context));
    }

    public function testItThrowsOnUnknownContext(): void
    {
        $faker = $this->createMock(Generator::class);

        $service = new ContentFaker($faker, [
            'email' => ['email'],
        ]);

        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage('Unknown context: username');

        $service->fake(StubFieldContext::from('username'));
    }
}
