<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Structure\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\Input\Nested;
use App\Models\Data\Input\Single;
use App\Modules\Structure\Domain\Structure;
use App\Modules\Structure\Infrastructure\StructureInputMapper;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StructureInputMapperTest extends TestCase
{
    private StructureInputMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new StructureInputMapper();
    }

    public function testThrowsExceptionInCaseOfMissingInputKey(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Missing mandatory input field: "key"');

        $input = [
            [
                'context' => StubFieldContext::cases()[0]->value,
            ],
        ];

        $this->mapper->map($input);
    }

    public function testThrowsExceptionIfInputNestedIsNotArray(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Input nested data must be an array');

        $input = [
            [
                'key' => 'foo',
                'nested' => 'not-an-array',
            ],
        ];

        $this->mapper->map($input);
    }

    public function testThrowsExceptionIfInputRepeatIsNotScalar(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Repeat must be a scalar or null');

        $input = [
            [
                'key' => 'foo',
                'nested' => [
                    [
                        'key' => 'bar',
                        'context' => StubFieldContext::cases()[0],
                    ],
                ],
                'repeat' => new \stdClass(),
            ],
        ];

        $this->mapper->map($input);
    }

    /**
     * @param list<array<string|list<array<string, string>>>> $input
     * @param array<Single|Nested> $inputs
     */
    #[DataProvider('inputOutputDataProvider')]
    public function testMapInputs(array $input, array $inputs): void
    {
        $expected = Structure::create(...$inputs);
        $actual = $this->mapper->map($input);

        static::assertEquals($expected, $actual);
    }

    /**
     * @return array<string, array{0: list<array<string, mixed>>, 1: list<Single|Nested>}>
     */
    public static function inputOutputDataProvider(): array
    {
        [
            $contextOne,
            $contextTwo,
            $contextThree,
            $contextFour,
            $contextFive,
        ] = fake()->randomElements(StubFieldContext::cases(), 5);

        return [
            'simple_object' => [
                [
                    [
                        'key' => 'foo',
                        'context' => $contextOne->value,
                    ],
                ],
                [
                    new Single('foo', $contextOne),
                ],
            ],
            'nested_simple_object_repeated' => [
                [
                    [
                        'key' => 'foo',
                        'nested' => [
                            [
                                'key' => 'bar',
                                'context' => $contextOne->value,
                            ],
                        ],
                        'repeat' => 3,
                    ],
                ],
                [
                    new Nested('foo', [
                        new Single('bar', $contextOne),
                    ], 3),
                ],
            ],
            'array_simple_object' => [
                [
                    [
                        'key' => 'field1',
                        'context' => $contextTwo->value,
                    ],
                    [
                        'key' => 'field2',
                        'context' => $contextThree->value,
                    ],
                ],
                [
                    // stub
                    new Single('field1', $contextTwo),
                    new Single('field2', $contextThree),
                ],
            ],
            'mixed_nested_simple_object' => [
                [
                    [
                        'key' => 'foo',
                        'nested' => [
                            [
                                'key' => 'foo-field',
                                'context' => $contextFour->value,
                            ],
                        ],
                    ],
                    [
                        'key' => 'bar',
                        'context' => $contextFive->value,
                    ],
                ],
                [
                    new Nested('foo', [
                        new Single('foo-field', $contextFour),
                    ], 0),
                    new Single('bar', $contextFive),
                ],
            ],

            ...self::multiDimensionalDataProvider(),
        ];
    }

    /**
     * @return array<string, array{0: list<array<string, mixed>>, 1: list<Single|Nested>}>
     */
    private static function multiDimensionalDataProvider(): array
    {
        [
            $contextOne,
            $contextTwo,
            $contextThree,
            $contextFour,
            $contextFive,
            $contextSix,
        ] = fake()->randomElements(StubFieldContext::cases(), 6);

        $foo = [
            'key' => 'foo',
            'nested' => [
                [
                    'key' => 'foo-field1',
                    'context' => $contextOne->value,
                ],
                [
                    'key' => 'foo-field2',
                    'nested' => [
                        [
                            'key' => 'foo-field3',
                            'context' => $contextTwo->value,
                        ],
                    ],
                ],
            ],
        ];
        $bar = [
            'key' => 'bar',
            'nested' => [
                [
                    'key' => 'bar-field1',
                    'context' => $contextThree->value,
                ],
                [
                    'key' => 'bar-field2',
                    'nested' => [
                        [
                            'key' => 'bar-field3',
                            'context' => $contextFour->value,
                        ],
                        [
                            'key' => 'bar-field4',
                            'nested' => [
                                [
                                    'key' => 'bar-field5',
                                    'context' => $contextFive->value,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $baz = [
            'key' => 'baz',
            'context' => $contextSix->value,
        ];

        $output = [
            // fooOutput
            new Nested('foo', [
                new Single('foo-field1', $contextOne),
                new Nested('foo-field2', [
                    new Single('foo-field3', $contextTwo),
                    ], 0)
            ], 0),
            // barOutput
            new Nested('bar', [
                new Single('bar-field1', $contextThree),
                new Nested('bar-field2', [
                    new Single('bar-field3', $contextFour),
                    new Nested('bar-field4', [
                        new Single('bar-field5', $contextFive),
                    ], 0),
                ], 0),
            ], 0),
            // bazOutput
            new Single('baz', $contextSix),
        ];

        $input = [$foo, $bar, $baz];

        return ['multidimensions' => [$input, $output]];
    }
}
