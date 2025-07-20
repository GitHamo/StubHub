<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure;

use App\Models\Data\Input\Nested;
use App\Models\Data\Input\Single;
use App\Models\Data\StructureInput;
use App\Models\Data\StubField;
use App\Models\Domain\Stub;
use App\Modules\Content\Domain\StubGenerator;
use InvalidArgumentException;

final readonly class ContentGeneratorService implements StubGenerator
{
    public function __construct(
        private ContentFaker $faker,
    ) {
    }

    #[\Override]
    public function generate(StructureInput ...$inputs): Stub
    {
        return $this->mapStub(...$inputs);
    }

    private function mapStub(StructureInput ...$inputs): Stub
    {
        return Stub::create(
            ...array_map(
                fn (StructureInput $input): StubField => $this->mapField($input),
                $inputs
            )
        );
    }

    private function mapField(StructureInput $input): StubField
    {
        return match (true) {
            $input instanceof Nested => $this->mapNestedField($input),
            $input instanceof Single => $this->mapSingleField($input),
            default => throw new InvalidArgumentException(
                sprintf('Unsupported input class: %s', get_debug_type($input))
            ),
        };
    }

    private function mapNestedField(Nested $input): StubField
    {
        if (empty($input->inputs)) {
            throw new InvalidArgumentException("Nested input '{$input->key}' must contain child inputs.");
        }

        if ($input->repeat < 0) {
            throw new InvalidArgumentException('Repeat must be 0 or greater.');
        }

        $value = $input->repeat
            ? $this->mapNestedFieldWithRepeat($input)
            : $this->mapStub(...$input->inputs);

        return $this->createField($input->key, $value);
    }

    private function mapSingleField(Single $input): StubField
    {
        /** @var null|bool|int|float|string|mixed[] $value */
        $value = $this->faker->fake($input->context);

        return $this->createField($input->key, $value);
    }

    /**
     * @return Stub[]
     */
    private function mapNestedFieldWithRepeat(Nested $nested): array
    {
        return array_map(
            fn () => $this->mapStub(...$nested->inputs),
            range(1, $nested->repeat)
        );
    }

    private function createField(string $key, mixed $value): StubField
    {
        return new StubField($key, $value);
    }
}
