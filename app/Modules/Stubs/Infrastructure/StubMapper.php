<?php

declare(strict_types=1);

namespace App\Modules\Stubs\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\Inputs\Input;
use App\Models\Data\Inputs\Nested;
use App\Models\Data\Inputs\Single;
use App\Models\Data\Stub;
use App\Models\Data\StubField;
use InvalidArgumentException;

abstract class StubMapper
{
    abstract protected function parseContext(StubFieldContext $context): mixed;

    /**
     * @param Input[] $inputs
     */
    public function parseInputs(Input ...$inputs): Stub
    {
        $fields = array_map(fn (Input $input): StubField => $this->parseInput($input), $inputs);

        return new Stub($fields);
    }

    private function parseInput(Input $input): StubField
    {
        return match(true) {
            $input instanceof Nested => $this->parseNestedInput($input),
            $input instanceof Single => $this->parseSingleInput($input),
            default => throw new InvalidArgumentException('Invalid input type'),
        };
    }

    private function parseSingleInput(Single $input): StubField
    {
        $value = $this->parseContext($input->context);

        return new StubField($input->key, $value);
    }

    private function parseNestedInput(Nested $input): StubField
    {
        $value = $input->repeat
            ? $this->parseNestedInputsAsArray($input)
            : $this->parseInputs(...$input->inputs);

        return new StubField($input->key, $value);
    }

    /**
     * @return Stub[]
     */
    private function parseNestedInputsAsArray(Nested $nested): array
    {
        return array_map(
            fn (): Stub => $this->parseInputs(...$nested->inputs),
            range(1, $nested->repeat)
        );
    }
}
