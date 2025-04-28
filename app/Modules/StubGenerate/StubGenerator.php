<?php

declare(strict_types=1);

namespace App\Modules\StubGenerate;

use App\Models\Data\Inputs\Input;
use App\Models\Data\Stub;

interface StubGenerator
{
    public function generate(Input ...$inputs): Stub;
}
