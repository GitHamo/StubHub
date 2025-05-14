<?php

declare(strict_types=1);

namespace App\Modules\Constraints\Domain;

use App\Models\Data\StructureInput;
use App\Models\Domain\Stub;
use App\Models\User;

interface ConstraintsCheck
{
    public function ensureUserCanCreateEndpoint(User $user): void;

    public function ensureStubSizeWithinLimits(User $user, Stub $stub): void;

    public function ensureInputRepeatWithinLimit(User $user, StructureInput ...$inputs): void;

    public function ensureInputDepthWithinLimit(User $user, StructureInput ...$inputs): void;
}
