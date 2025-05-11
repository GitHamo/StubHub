<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Models\Data\Input;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    public function testIsSerializable(): void
    {
        $expected = ['key' => $key = 'foo'];

        $actual = new class ($key) extends Input {};

        static::assertSame($expected, $actual->jsonSerialize());
    }
}
