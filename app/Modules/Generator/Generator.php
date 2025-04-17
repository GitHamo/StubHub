<?php

declare(strict_types=1);

namespace App\Modules\Generator;

use App\Models\Data\Stub;

interface Generator
{
    public function generate(string $rawInput): Stub;
}
