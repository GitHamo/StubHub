<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\StrictJson;
use JsonException;
use PHPUnit\Framework\TestCase;
use stdClass;

class StrictJsonTest extends TestCase
{
    public function testItEncodesValidData(): void
    {
        $data = [
            'name' => 'Jöhn Dœ 🚀',
            'age' => '42',
            'height' => 1.85,
        ];

        $expected = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        static::assertSame($expected, StrictJson::encode($data));
    }

    public function testItThrowsOnInvalidEncoding(): void
    {
        $data = tmpfile(); // cannot be JSON encoded

        static::expectException(JsonException::class);

        StrictJson::encode($data);
    }

    public function testItDecodesValidJsonToAssocArray(): void
    {
        $json = '{"name":"John","age":30, "height":"1.85"}';

        $expected = ['name' => 'John', 'age' => 30, 'height' => '1.85'];

        static::assertSame($expected, StrictJson::decode($json));
    }

    public function testItDecodesValidJsonToObject(): void
    {
        $json = '{"name":"Jane","age":25}';

        $result = StrictJson::decode($json, false);

        static::assertInstanceOf(stdClass::class, $result);
        static::assertSame('Jane', $result->name);
        static::assertSame(25, $result->age);
    }

    public function testItThrowsOnInvalidJson(): void
    {
        $invalidJson = '{"name":"Missing ending quote}';

        static::expectException(JsonException::class);

        StrictJson::decode($invalidJson);
    }

    public function testItPreservesUnicodeCharacters(): void
    {
        $data = ['text' => 'Привет 🌍'];

        $encoded = StrictJson::encode($data);

        static::assertStringContainsString('Привет', $encoded);
        static::assertStringContainsString('🌍', $encoded);

        $decoded = StrictJson::decode($encoded);

        static::assertSame($data, $decoded);
    }

    public function testItThrowsOnDepthExceeded(): void
    {
        // Create a deeply nested JSON string
        $json = str_repeat('{"a":', 513) . 'null' . str_repeat('}', 513);

        static::expectException(JsonException::class);

        StrictJson::decode($json); // exceeds 512 depth limit
    }
}
