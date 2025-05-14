<?php

declare(strict_types=1);

namespace App\Modules\Constraints\Infrastructure;

use App\Models\Data\Input\Nested;
use App\Models\Data\StructureInput;

readonly class InputRepeatMapper
{
    public function max(StructureInput ...$inputs): int
    {
        $max = array_map(fn (StructureInput $input): int => $this->maxRepeatInput($input), $inputs);

        return $max ? $max[0] : 0;
    }

    private function maxRepeatInput(StructureInput $input): int
    {
        if (!$input instanceof Nested) {
            return 0;
        }

        $repeats = array_map(fn (StructureInput $input): int => $this->maxRepeatInput($input), $input->inputs);

        return max($input->repeat, ...$repeats);
    }
}
