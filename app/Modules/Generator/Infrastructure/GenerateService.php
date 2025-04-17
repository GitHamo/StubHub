<?php

declare(strict_types=1);

namespace App\Modules\Generator\Infrastructure;

use App\Modules\Generator\Generator;
use App\Models\Data\Stub;

readonly class GenerateService implements Generator
{
    public function __construct(
        private InputMapper $mapper,
        private FakerService $faker,
    ) {

    }

    public function generate(string $rawInput): Stub
    {
        return $this->faker->generate(
            ...$this->mapper->map($rawInput)
        );
    }
}
