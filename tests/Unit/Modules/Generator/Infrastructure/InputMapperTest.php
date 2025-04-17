<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Generator\Infrastructure;

use App\Enums\ContextEnum;
use App\Exceptions\JsonParseException;
use App\Models\Data\Input;
use App\Modules\Generator\Infrastructure\InputMapper;
use App\Support\JsonParser;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InputMapperTest extends TestCase
{
    private InputMapper $mapper;
    private JsonParser|MockObject $parser;


    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new InputMapper(
            $this->parser = $this->createMock(JsonParser::class),
        );
    }
    public function testParseReturnsCorrectInputObjects(): void
    {
        // Valid JSON input (mocked parsed data)
        $json = '[{"key": "field1", "type": "currency_code"}, {"key": "field2", "type": "email"}]';
        $parsedData = [
            ['key' => 'field1', 'type' => 'currency_code'],
            ['key' => 'field2', 'type' => 'email']
        ];

        // Mock the JsonParser to return the parsed data
        $this->parser->expects($this->once())
            ->method('parse')
            ->with($json)
            ->willReturn($parsedData);

        $expected = [
            new Input('field1', ContextEnum::CURRENCY_CODE),
            new Input('field2', ContextEnum::EMAIL),
        ];

        $actual = $this->mapper->map($json);

        static::assertEquals($expected, $actual);
    }

    public function testParseThrowsJsonParseExceptionForInvalidJsonFormat(): void
    {
        // Invalid JSON format (not a list)
        $json = '{"key": "field1", "type": "currency_code"}'; // Associative array instead of list

        // Mock the JsonParser to return invalid format
        $this->parser->expects($this->once())
            ->method('parse')
            ->with($json)
            ->willReturn(['key' => 'field1', 'type' => 'currency_code']); // Not a list

        static::expectException(JsonParseException::class);
        static::expectExceptionMessage('JSON must decode to a listed array.');

        $this->mapper->map($json);
    }

    public function testParseThrowsInvalidArgumentExceptionForInvalidItemDataFormat(): void
    {
        // Invalid item data format (not an associative array)
        $json = '[["field1", "currency_code"]]'; // List of lists instead of associative arrays

        // Mock the JsonParser to return invalid item format
        $this->parser->expects($this->once())
            ->method('parse')
            ->with($json)
            ->willReturn([['field1', 'currency_code']]); // List of lists instead of associative arrays

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Item data must decode to an associated array.');

        $this->mapper->map($json);
    }

    public function testParseThrowsInvalidArgumentExceptionForMissingKeyField(): void
    {
        // Missing the "key" field in the item
        $json = '[{"type": "currency_code"}]'; // Missing "key"

        $parsedData = [['type' => 'currency_code']]; // Missing "key"

        // Mock the JsonParser to return parsed data
        $this->parser->expects($this->once())
            ->method('parse')
            ->with($json)
            ->willReturn($parsedData);

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Missing mandatory input field: "key"');

        $this->mapper->map($json);
    }

    public function testParseThrowsInvalidArgumentExceptionForMissingTypeField(): void
    {
        // Missing the "type" field in the item
        $json = '[{"key": "field1"}]'; // Missing "type"

        $parsedData = [['key' => 'field1']]; // Missing "type"

        // Mock the JsonParser to return parsed data
        $this->parser->expects($this->once())
            ->method('parse')
            ->with($json)
            ->willReturn($parsedData);

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Missing mandatory input field: "type"');

        $this->mapper->map($json);
    }
}
