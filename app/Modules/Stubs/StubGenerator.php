<?php

declare(strict_types=1);

namespace App\Modules\Stubs;

use App\Models\Data\Stub;

interface StubGenerator
{
    /**
     * @param list<array<string, mixed>> $rawInputs
     */
    public function generate(array $rawInputs): Stub;
}
