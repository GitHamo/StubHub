<?php

declare(strict_types=1);

namespace App\Modules\Content\Infrastructure;

use App\Models\Data\Input;
use App\Models\Data\Input\Nested;
use App\Models\Data\Input\Single;
use App\Models\Data\Stub;
use App\Models\Data\StubField;
use App\Modules\Content\Domain\Generator;
use InvalidArgumentException;

final readonly class ContentGeneratorService implements Generator
{
    public function __construct(
        private ContentFaker $faker,
    ) {
    }

    #[\Override]
    public function generate(Input ...$inputs): Stub
    {
        return $this->mapStub(...$inputs);
    }

    private function mapStub(Input ...$inputs): Stub
    {
        return new Stub(
            array_map(
                fn (Input $input): StubField => $this->mapField($input),
                $inputs
            )
        );
    }

    private function mapField(Input $input): StubField
    {
        return match(true) {
            $input instanceof Nested => $this->mapNestedField($input),
            $input instanceof Single => $this->mapSingleField($input),
            default => throw new InvalidArgumentException(
                sprintf('Unsupported input class: %s', get_debug_type($input))
            ),
        };
    }

    private function mapSingleField(Single $input): StubField
    {
        /** @var null|bool|int|float|string|mixed[] $value */
        $value = $this->faker->parse($input->context);

        return $this->createField($input->key, $value);
    }

    private function mapNestedField(Nested $input): StubField
    {
        if (empty($input->inputs)) {
            throw new InvalidArgumentException("Nested input '{$input->key}' must contain child inputs.");
        }

        if ($input->repeat < 0) {
            throw new InvalidArgumentException("Repeat must be 0 or greater.");
        }

        $value = $input->repeat
            ? $this->mapNestedFieldAsArray($input)
            : $this->mapStub(...$input->inputs);

        return $this->createField($input->key, $value);
    }

    /**
     * @return Stub[]
     */
    private function mapNestedFieldAsArray(Nested $nested): array
    {
        return array_fill(0, $nested->repeat, $this->mapStub(...$nested->inputs));
    }

    private function createField(string $key, mixed $value): StubField
    {
        return new StubField($key, $value);
    }
}
