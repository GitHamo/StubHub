<?php

declare(strict_types=1);

namespace App\Modules\Constraints\Infrastructure;

use App\Models\Data\Input\Nested;
use App\Models\Data\StructureInput;

readonly class InputDepthMapper
{
    public function highest(StructureInput ...$inputs): int
    {
        $max = 0;

        foreach ($inputs as $input) {
            $depth = $this->calculateDepth($input);
            if ($depth > $max) {
                $max = $depth;
            }
        }

        return $max;
    }

    private function calculateDepth(StructureInput $input, int $current = 1): int
    {
        if (!$input instanceof Nested) {
            return $current;
        }

        $maxNestedDepth = $current;

        foreach ($input->inputs as $child) {
            $depth = $this->calculateDepth($child, $current + 1);
            if ($depth > $maxNestedDepth) {
                $maxNestedDepth = $depth;
            }
        }

        return $maxNestedDepth;
    }
}
