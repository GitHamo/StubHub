<?php

declare(strict_types=1);

namespace App\Modules\Stubs\Infrastructure;

use App\Models\Data\StubInput;
use App\Modules\Stubs\StubGenerator;
use App\Models\Data\Stub;

readonly class StubGenerateService implements StubGenerator
{
    public function __construct(
        private InputMapper $inputMapper,
        private FakerService $faker,
    ) {
    }

    #[\Override]
    public function generate(array $rawInputs): Stub
    {
        $inputs = array_map(fn (array $itemData): StubInput => $this->inputMapper->map($itemData), $rawInputs);

        return $this->faker->generate(...$inputs);
    }
}
