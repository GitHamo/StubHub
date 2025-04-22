<?php

declare(strict_types=1);

namespace App\Modules\Stubs\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\StubInput;
use InvalidArgumentException;

readonly class InputMapper
{
    private const string ERROR_MESSAGE_MISSING_MANDATORY_FIELD = 'Missing mandatory input field: "%s"';
    private const string INPUT_KEY = "key";
    private const string INPUT_CONTEXT = "context";

    public function map(array $rawInput): StubInput
    {
        if (array_is_list($rawInput)) {
            throw new InvalidArgumentException('Input data must decode to an associated array.');
        }

        if (!array_key_exists(self::INPUT_KEY, $rawInput)) {
            throw new InvalidArgumentException(
                sprintf(
                    self::ERROR_MESSAGE_MISSING_MANDATORY_FIELD,
                    self::INPUT_KEY,
                )
            );
        }

        if (!array_key_exists(self::INPUT_CONTEXT, $rawInput)) {
            throw new InvalidArgumentException(
                sprintf(
                    self::ERROR_MESSAGE_MISSING_MANDATORY_FIELD,
                    self::INPUT_CONTEXT,
                )
            );
        }

        return new StubInput($rawInput[self::INPUT_KEY], StubFieldContext::fromName($rawInput[self::INPUT_CONTEXT]));
    }
}
