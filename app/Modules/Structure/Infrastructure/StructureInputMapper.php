<?php

declare(strict_types=1);

namespace App\Modules\Structure\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\Input\Nested;
use App\Models\Data\Input\Single;
use App\Models\Data\StructureInput;
use App\Modules\Structure\Domain\Structure;
use App\Modules\Structure\Domain\InputMapper;
use InvalidArgumentException;

final readonly class StructureInputMapper implements InputMapper
{
    private const string ERROR_MESSAGE_MISSING_MANDATORY_FIELD = 'Missing mandatory input field: "%s"';
    private const string INPUT_KEY = "key";
    private const string INPUT_CONTEXT = "context";
    private const string INPUT_NESTED = "nested";
    private const string INPUT_REPEAT = "repeat";

    #[\Override]
    public function map(array $rawData): Structure
    {
        return Structure::create(
            ...array_map([$this, 'mapInput'], $rawData)
        );
    }

    /**
     * @param array<string, mixed> $inputData
     * @throws \InvalidArgumentException
     * @return Single|Nested
     */
    private function mapInput(array $inputData): StructureInput
    {
        if (false === $this->isNestedInput($inputData)) {
            return $this->mapSingleInput($inputData);
        }

        /** @var mixed */
        $nested = $inputData[self::INPUT_NESTED];

        /** @var string */
        $key = $this->findOrFail(self::INPUT_KEY, $inputData);
        $repeat = $this->find(self::INPUT_REPEAT, $inputData);

        if (!is_scalar($repeat) && $repeat !== null) {
            throw new InvalidArgumentException('Repeat must be a scalar or null');
        }

        $repeat = intval($repeat);

        $isNotIterable = !is_array($nested) || !array_is_list($nested);
        if ($isNotIterable) {
            throw new InvalidArgumentException('Input nested data must be an array');
        }

        /** @var list<array<string, mixed>> $nested */
        return $this->mapNestedInput($key, $nested, $repeat);
    }

    /**
     * @param array<string, mixed> $input
     */
    private function isNestedInput(array $input): bool
    {
        return isset($input[self::INPUT_NESTED]);
    }

    /**
     * @param list<array<string, mixed>> $nested
     */
    private function mapNestedInput(string $key, array $nested, int $repeat): Nested
    {
        $nestedInputs = array_map([$this, 'mapInput'], $nested);

        return new Nested($key, $nestedInputs, $repeat);
    }

    /**
     * @param array<string, mixed> $inputData
     */
    private function mapSingleInput(array $inputData): Single
    {
        /** @var string */
        $key = $this->findOrFail(self::INPUT_KEY, $inputData);
        /** @var string */
        $context = $this->findOrFail(self::INPUT_CONTEXT, $inputData);

        return new Single($key, StubFieldContext::fromName($context));
    }

    /**
     * @param array<string, mixed> $haystack
     */
    private function findOrFail(string $key, array $haystack): mixed
    {
        $value = $this->find($key, $haystack);

        if ($value !== null) {
            return $value;
        }

        throw new InvalidArgumentException(
            sprintf(
                self::ERROR_MESSAGE_MISSING_MANDATORY_FIELD,
                $key,
            )
        );
    }

    /**
     * @param array<string, mixed> $haystack
     */
    private function find(string $key, array $haystack): mixed
    {
        if (array_key_exists($key, $haystack)) {
            return $haystack[$key];
        }

        return null;
    }
}
