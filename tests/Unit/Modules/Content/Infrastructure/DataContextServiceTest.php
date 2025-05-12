<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Content\Infrastructure;

use App\Enums\StubFieldContext;
use App\Modules\Content\Infrastructure\DataContextService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DataContextServiceTest extends TestCase
{
    private DataContextService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new DataContextService();
    }

    public function testShouldCoverAllEnumCasesInFlatMap(): void
    {
        $map = $this->service->flatMap();
        $enumCases = StubFieldContext::cases();

        static::assertCount(count($enumCases), $map, 'flatMap() should contain same number of entries as enum cases');
    }

    public function testShouldCoverAllEnumCasesInCategoryMapInputs(): void
    {
        $map = $this->service->categoryMap();
        $totalInputs = 0;
        foreach ($map as $category) {
            $totalInputs += count($category['inputs']);
        }

        $enumCases = StubFieldContext::cases();
        static::assertCount($totalInputs, $enumCases, 'Sum of inputs in categoryMap() should match enum case count');
    }


    public function testShouldReturnFlatMapWithAllEnumCases(): void
    {
        $map = $this->service->flatMap();
        $expectedKeys = array_map(fn ($case) => $case->value, StubFieldContext::cases());

        static::assertEqualsCanonicalizing($expectedKeys, array_keys($map));

        foreach ($map as $values) {
            static::assertCount(2, $values, "Each flatMap entry must contain 2 values [method, type]");
            // @phpstan-ignore-next-line
            static::assertIsString($values[0]);
            // @phpstan-ignore-next-line
            static::assertIsString($values[1]);
        }
    }

    public function testShouldReturnCategoryMapWithValidStructure(): void
    {
        $map = $this->service->categoryMap();

        static::assertNotEmpty($map);

        foreach ($map as $categoryData) {
            static::assertArrayHasKey('label', $categoryData);
            static::assertArrayHasKey('inputs', $categoryData);
            // @phpstan-ignore-next-line
            static::assertIsArray($categoryData['inputs']);

            foreach ($categoryData['inputs'] as $input) {
                static::assertArrayHasKey('label', $input);
                static::assertArrayHasKey('input', $input);
                //@phpstan-ignore-next-line
                static::assertIsString($input['label']);
                //@phpstan-ignore-next-line
                static::assertIsString($input['input']);
            }
        }
    }

    #[DataProvider('validMetadataTypesDataProvider')]
    public function testShouldReturnMetadataMapWithCorrectKeys(string $type1, string $type2): void
    {
        $refClass = new \ReflectionClass(DataContextService::class);
        $method = $refClass->getMethod('metadataMap');
        $method->setAccessible(true);

        /** @var array<string, array{string, string}> $map */
        $map = $method->invoke($this->service, $type1, $type2);

        // @phpstan-ignore-next-line
        static::assertIsArray($map);

        foreach (StubFieldContext::cases() as $case) {
            static::assertArrayHasKey($case->value, $map);
            static::assertCount(2, $map[$case->value]);
            // @phpstan-ignore-next-line
            static::assertIsString($map[$case->value][0]);
            // @phpstan-ignore-next-line
            static::assertIsString($map[$case->value][1]);
        }
    }

    /**
     * @return string[][]
     */
    public static function validMetadataTypesDataProvider(): array
    {
        return [
            ['method', 'type'],
            ['label', 'input'],
        ];
    }
}
