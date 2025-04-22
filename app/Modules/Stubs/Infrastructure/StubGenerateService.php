<?php

declare(strict_types=1);

namespace App\Modules\Stubs\Infrastructure;

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
        $inputs = $this->inputMapper->mapInputs($rawInputs);

        return $this->faker->generate(...$inputs);
    }
}
