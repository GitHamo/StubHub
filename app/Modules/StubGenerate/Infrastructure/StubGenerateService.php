<?php

declare(strict_types=1);

namespace App\Modules\StubGenerate\Infrastructure;

use App\Models\Data\Inputs\Input;
use App\Models\Data\Stub;
use App\Modules\StubGenerate\StubGenerator;

readonly class StubGenerateService implements StubGenerator
{
    public function __construct(
        private FakerStubMapper $mapper,
    ) {
    }

    #[\Override]
    public function generate(Input ...$inputs): Stub
    {
        return $this->mapper->parseInputs(...$inputs);
    }
}
