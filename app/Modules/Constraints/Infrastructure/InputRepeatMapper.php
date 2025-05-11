<?php

declare(strict_types=1);

namespace App\Modules\Constraints\Infrastructure;

use App\Models\Data\Input;
use App\Models\Data\Input\Nested;

final readonly class InputRepeatMapper
{
    public function max(Input ...$inputs): int
    {
        $max = array_map(fn (Input $input): int => $this->maxRepeatInput($input), $inputs);

        return $max ? $max[0] : 0;
    }

    private function maxRepeatInput(Input $input): int
    {
        if (!$input instanceof Nested) {
            return 0;
        }

        $repeats = array_map(fn (Input $input): int => $this->maxRepeatInput($input), $input->inputs);

        return max($input->repeat, ...$repeats);
    }
}
