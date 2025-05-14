<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Models\Data\StructureInput;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    public function testIsSerializableIntoArray(): void
    {
        $expected = ['key' => $key = 'foo'];

        $actual = new class($key) extends StructureInput {};

        static::assertSame($expected, $actual->toArray());
    }
}
