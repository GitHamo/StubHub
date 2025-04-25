<?php

declare(strict_types=1);

namespace App\Modules\Stubs\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\Inputs\Input;
use App\Models\Data\Inputs\Nested;
use App\Models\Data\Inputs\Single;
use InvalidArgumentException;

readonly class InputMapper
{
    private const string ERROR_MESSAGE_MISSING_MANDATORY_FIELD = 'Missing mandatory input field: "%s"';
    private const string INPUT_KEY = "key";
    private const string INPUT_CONTEXT = "context";
    private const string INPUT_NESTED = "nested";

    /**
     * @param list<array<string, mixed>> $rawInputData
     * @throws \InvalidArgumentException
     * @return Input[]
     */
    public function mapInputs(array $rawInputData): array
    {
        return array_map([$this, 'mapInput'], $rawInputData);
    }

    /**
     * @param array<string, mixed> $rawInput
     * @return Nested|Single
     */
    private function mapInput(array $rawInput): Input
    {
        if (array_key_exists(self::INPUT_NESTED, $rawInput)) {

            $nested = $rawInput[self::INPUT_NESTED];

            if (!is_array($nested) || !array_is_list($nested)) {
                throw new InvalidArgumentException('Input nested data must be an array');
            }

            /** @var string */
            $key = $this->findOrFail(self::INPUT_KEY, $rawInput);

            return $this->mapNestedInput($key, $nested);
        }

        return $this->mapSingleInput($rawInput);
    }

    /**
     * @param list<array<string, mixed>> $nested
     */
    private function mapNestedInput(string $key, array $nested): Nested
    {
        $nestedInputs = array_map([$this, 'mapInput'], $nested);

        return new Nested($key, $nestedInputs);
    }

    /**
     * @param array<string, mixed> $rawInput
     */
    private function mapSingleInput(array $rawInput): Single
    {
        /** @var string */
        $key = $this->findOrFail(self::INPUT_KEY, $rawInput);
        /** @var string */
        $context = $this->findOrFail(self::INPUT_CONTEXT, $rawInput);

        return new Single($key, StubFieldContext::fromName($context));
    }

    /**
     * @param array<string, mixed> $haystack
     */
    private function findOrFail(string $key, array $haystack): mixed
    {
        if (!array_key_exists($key, $haystack)) {
            throw new InvalidArgumentException(
                sprintf(
                    self::ERROR_MESSAGE_MISSING_MANDATORY_FIELD,
                    $key,
                )
            );
        }

        return $haystack[$key];
    }
}
