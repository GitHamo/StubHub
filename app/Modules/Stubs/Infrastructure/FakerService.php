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
    private array $contextsMap;

    public function __construct(private Generator $generator)
    {
        $this->contextsMap = StubFieldContextMapper::flatMap();
    }

    public function generate(Input ...$inputs): Stub
    {
        $fields = array_map(fn (Input $input): StubField => $this->generateStubField($input), $inputs);

        return new Stub($fields);
    }


    private function generateNestedField(Nested $nested): StubField
    {
        $fields = [];

        foreach ($nested->inputs as $nestedInput) {
            if ($nestedInput instanceof Nested) {
                $fields[] = $this->generateStubField($nestedInput);
                continue;
            }

            if ($nestedInput instanceof Single) {
                $fields[] = $this->generateSingleField($nestedInput);
                continue;
            }

            throw new InvalidArgumentException('Invalid input type');
        }

        return new StubField($nested->key, new Stub($fields));
    }

    private function generateSingleField(Single $input): StubField
    {
        $method = $this->contextsMap[$input->context->value][0];
        $value = $this->generator->$method();

        return new StubField($input->key, $value);
    }

    private function generateStubField(Input $input): StubField
    {
        return match(true) {
            $input instanceof Nested => $this->generateNestedField($input),
            $input instanceof Single => $this->generateSingleField($input),
            default => throw new InvalidArgumentException('Invalid input type'),
        };
    }
}
