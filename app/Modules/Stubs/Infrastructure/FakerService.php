<?php

declare(strict_types=1);

namespace App\Modules\Stubs\Infrastructure;

use App\Models\Data\StubField;
use App\Models\Data\StubInput;
use App\Models\Data\Stub;
use App\Support\StubFieldContextMapper;
use Faker\Generator;

readonly class FakerService
{
    public function __construct(private Generator $generator)
    {
    }

    public function generate(StubInput ...$inputs): Stub
    {
        $contextsMap = StubFieldContextMapper::flatMap();

        $fields = array_map(function (StubInput $input) use ($contextsMap): StubField {
            $method = $contextsMap[$input->context->value][0];

            $value = $this->generator->$method();

            return new StubField($input->key, $value);
        }, $inputs);

        return new Stub($fields);
    }
}
