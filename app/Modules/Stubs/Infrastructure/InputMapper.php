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
     * @param array<mixed> $rawInputData
     * @throws \InvalidArgumentException
     * @return Input[]
     */
    public function mapInputs(array $rawInputData): array
    {
        if (!array_is_list($rawInputData)) {
            throw new InvalidArgumentException('Input data must decode to listed array.');
        }

        $inputs = [];

        foreach ($rawInputData as $rawInput) {

            if (array_key_exists(self::INPUT_NESTED, $rawInput)) {

                $nested = $rawInput[self::INPUT_NESTED];

                if (!is_array($nested) || !array_is_list($nested)) {
                    throw new InvalidArgumentException('Input nested data must be an array');
                }

                $key = $this->findOrFail(self::INPUT_KEY, $rawInput);

                $inputs[] = $this->mapNestedInput($key, $nested);

                continue;
            }

            $inputs[] = $this->mapSingleInput($rawInput);
        }

        return $inputs;
    }

    private function mapNestedInput(string $key, array $nested): Nested
    {
        if (!array_is_list($nested)) {
            throw new InvalidArgumentException('Input nested data must be a listed array');
        }

        $nestedInputs = [];

        foreach ($nested as $nestedRawInput) {
            if (array_key_exists(self::INPUT_NESTED, $nestedRawInput)) {
                if (!is_array($nestedRawInput[self::INPUT_NESTED])) {
                    throw new InvalidArgumentException('Input nested data must be an array');
                }
                $nestedInputKey = $this->findOrFail(self::INPUT_KEY, $nestedRawInput);

                $nestedInputs[] = $this->mapNestedInput($nestedInputKey, $nestedRawInput[self::INPUT_NESTED]);
                continue;
            }

            $nestedInputs[] = $this->mapSingleInput($nestedRawInput);
        }

        return new Nested($key, $nestedInputs);
    }

    private function mapSingleInput(array $rawInput): Single
    {
        $key = $this->findOrFail(self::INPUT_KEY, $rawInput);
        $context = $this->findOrFail(self::INPUT_CONTEXT, $rawInput);

        return new Single($key, StubFieldContext::fromName($context));
    }

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
