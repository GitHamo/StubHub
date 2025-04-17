<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Exceptions\JsonParseException;
use App\Support\JsonParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class JsonParserTest extends TestCase
{
    private JsonParser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new JsonParser();
    }

    #[DataProvider('validJsonDataProvider')]
    public function testParseValidJson(string $json, array $expected): void
    {
        $actual = $this->parser->parse($json);

        static::assertEquals($expected, $actual);
    }

    #[DataProvider('invalidJsonDataProvider')]
    public function testParseInvalidJsonThrowsJsonParseException(string $json): void
    {
        static::expectException(JsonParseException::class);

        $this->parser->parse($json);
    }

    public function testParseEmptyJsonString(): void
    {
        $actual = $this->parser->parse('{}');

        static::assertEquals([], $actual);
    }

    public function testParseJsonWithEmptyObject(): void
    {
        $actual = $this->parser->parse('{"key": {}}');

        static::assertEquals(['key' => []], $actual);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function validJsonDataProvider(): array
    {
        return [
            'simple valid json' => ['{"key": "value"}', ['key' => 'value']],
            'nested valid json' => ['{"user": {"name": "John", "age": 30}}', ['user' => ['name' => 'John', 'age' => 30]]],
            'array valid json' => ['[1, 2, 3, 4]', [1, 2, 3, 4]],
            'empty json object' => ['{}', []],
            'json with boolean' => ['{"isActive": true}', ['isActive' => true]],
            'json with null value' => ['{"isActive": null}', ['isActive' => null]],
        ];
    }

    /**
     * @return array<string, array<string>>
     */
    public static function invalidJsonDataProvider(): array
    {
        return [
            'missing closing bracket' => ['{"key": "value"'],
            'invalid json string' => ['{"user": "John", age: 30}'],
            'extra commas in json' => ['{"key": "value",}'],
            'empty string' => [''],
        ];
    }
}
