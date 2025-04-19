<?php

declare(strict_types=1);

namespace App\Modules\Stubs;

use App\Models\Data\Stub;

interface StubGenerator
{
    /**
     * @param array<array-key, array<string, mixed>> $rawInputs
     */
    public function generate(array $rawInputs): Stub;
}
