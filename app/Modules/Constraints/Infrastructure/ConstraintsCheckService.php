<?php

declare(strict_types=1);

namespace App\Modules\Constraints\Infrastructure;

use App\Models\Data\StructureInput;
use App\Models\Domain\Stub;
use App\Models\User;
use App\Modules\Constraints\Domain\ConstraintsCheck;
use App\Modules\Constraints\Infrastructure\InputRepeatMapper;
use App\Support\StrictJson;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Access\Authorizable;

readonly class ConstraintsCheckService implements ConstraintsCheck
{
    public function __construct(
        private InputRepeatMapper $inputRepeatMapper,
        private InputDepthMapper $inputDepthMapper,
    ) {
    }

    #[\Override]
    public function ensureUserCanCreateEndpoint(Authorizable $user): void
    {
        if (!$user->can('createEndpoint', User::class)) {
            throw new AuthorizationException('User cannot create more endpoints');
        }
    }

    #[\Override]
    public function ensureStubSizeWithinLimits(Authorizable $user, Stub $stub): void
    {
        $content = StrictJson::encode($stub);
        $size = mb_strlen($content, '8bit');

        if (!$user->can('createStubOfSize', [User::class, $size])) {
            throw new AuthorizationException('Stub size exceeds allowed limit');
        }
    }

    #[\Override]
    public function ensureInputRepeatWithinLimit(Authorizable $user, StructureInput ...$inputs): void
    {
        $maxRepeat = $this->calculateMaxRepeat(...$inputs);

        if (!$user->can('createStubWithRepeat', [User::class, $maxRepeat])) {
            throw new AuthorizationException('Stub object repeat exceeds allowed limit');
        }
    }

    #[\Override]
    public function ensureInputDepthWithinLimit(User $user, StructureInput ...$inputs): void
    {
        $maxDepth = $this->calculateMaxDepth(...$inputs);

        if (!$user->can('createStubWithDepth', [User::class, $maxDepth])) {
            throw new AuthorizationException('Stub object depth exceeds allowed limit');
        }
    }

    private function calculateMaxRepeat(StructureInput ...$inputs): int
    {
        return $this->inputRepeatMapper->max(...$inputs);
    }

    private function calculateMaxDepth(StructureInput ...$inputs): int
    {
        return $this->inputDepthMapper->highest(...$inputs);
    }
}
