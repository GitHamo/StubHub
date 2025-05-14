<?php

declare(strict_types=1);

namespace App\Modules\Content\Domain;

use App\Models\Data\StructureInput;
use App\Models\Domain\Stub;

interface StubGenerator
{
    public function generate(StructureInput ...$inputs): Stub;
}
