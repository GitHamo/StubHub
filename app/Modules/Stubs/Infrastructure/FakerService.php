<?php

declare(strict_types=1);

namespace App\Modules\Stubs\Infrastructure;

use App\Models\Data\Inputs\Input;
use App\Models\Data\Inputs\Nested;
use App\Models\Data\Inputs\Single;
use App\Models\Data\StubField;
use App\Models\Data\Stub;
use App\Support\StubFieldContextMapper;
use Faker\Generator;
use InvalidArgumentException;

readonly class FakerService
{
    /**
     * @var string[][]
     */
    private array $contextsMap;

    public function __construct(private Generator $generator)
    {
        $this->contextsMap = StubFieldContextMapper::flatMap();
    }

    public function generate(Input ...$inputs): Stub
    {
        return $this->parseInputs(...$inputs);
    }

    /**
     * @param Input[] $inputs
     */
    private function parseInputs(Input ...$inputs): Stub
    {
        $fields = array_map(fn (Input $input): StubField => $this->parseInput($input), $inputs);

        return new Stub($fields);
    }

    private function parseInput(Input $input): StubField
    {
        $value = match(true) {
            $input instanceof Nested => $this->parseNestedInput($input),
            $input instanceof Single => $this->parseSingleInput($input),
            default => throw new InvalidArgumentException('Invalid input type'),
        };

        return new StubField($input->key, $value);
    }

    private function parseSingleInput(Single $input): StubField
    {
        $method = $this->contextsMap[$input->context->value][0];
        $value = $this->generator->$method();

        return new StubField($input->key, $value);
    }

    private function parseNestedInput(Nested $input): StubField
    {
        $value = $input->repeat
            ? $this->parseNestedInputsAsArray($input)
            : $this->parseNestedInputsAsObject($input);

        return new StubField($input->key, $value);
    }

    private function parseNestedInputsAsArray(Nested $nested): StubField
    {
        $repeat = $nested->repeat;

        $values = array_map(
            fn () => $this->parseNestedInputsAsObject($nested),
            range(1, $repeat)
        );

        return new StubField($nested->key, $values);
    }

    private function parseNestedInputsAsObject(Nested $nested): StubField
    {
        $fields = array_map(fn (Input $nestedInput): StubField => $this->parseInput($nestedInput), $nested->inputs);

        return new StubField($nested->key, new Stub($fields));
    }
}
