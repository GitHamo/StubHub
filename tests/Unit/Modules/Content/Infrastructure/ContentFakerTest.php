<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Content\Infrastructure;

use App\Enums\StubFieldContext;
use App\Modules\Content\Infrastructure\ContentFaker;
use PHPUnit\Framework\TestCase;

class ContentFakerTest extends TestCase
{
    public function testItThrowsOnUnknownContext(): void
    {
        $faker = $this->createMock(\Faker\Generator::class);

        $service = new ContentFaker($faker, [
            'email' => ['email'],
        ]);

        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage('Unknown context: username');

        $service->fake(StubFieldContext::from('username'));
    }
}
