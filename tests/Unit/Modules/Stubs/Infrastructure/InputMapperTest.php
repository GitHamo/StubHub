<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Stubs\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\StubInput;
use App\Modules\Stubs\Infrastructure\InputMapper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class InputMapperTest extends TestCase
{
    private InputMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new InputMapper();
    }

    public function testParseReturnsCorrectInputObject(): void
    {
        $data = ['key' => 'field1', 'context' => 'currency_code'];

        $expected = new StubInput('field1', StubFieldContext::CURRENCY_CODE);
        $actual = $this->mapper->map($data);

        static::assertEquals($expected, $actual);
    }

    public function testParseThrowsExceptionWhenInputIsList(): void
    {
        $listInput = ['field1', 'currency_code'];

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Input data must decode to an associated array.');

        $this->mapper->map($listInput);
    }

    public function testParseThrowsExceptionWhenKeyMissing(): void
    {
        $data = ['type' => 'currency_code']; // missing 'key'

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Missing mandatory input field: "key"');

        $this->mapper->map($data);
    }

    public function testParseThrowsExceptionWhenContextMissing(): void
    {
        $data = ['key' => 'field1']; // missing 'context'

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Missing mandatory input field: "context"');

        $this->mapper->map($data);
    }
}
