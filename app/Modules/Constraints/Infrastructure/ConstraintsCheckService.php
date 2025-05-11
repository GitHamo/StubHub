<?php

declare(strict_types=1);

namespace App\Modules\Constraints\Infrastructure;

use App\Models\Data\Input;
use App\Models\Data\Stub;
use App\Models\User;
use App\Modules\Constraints\Domain\ConstraintsCheck;
use App\Support\InputRepeatMapper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Access\Authorizable;

readonly class ConstraintsCheckService implements ConstraintsCheck
{
    public function __construct(private InputRepeatMapper $inputRepeatMapper)
    {
    }

    #[\Override]
    public function ensureUserCanCreateEndpoint(Authorizable $user): void
    {
        if (! $user->can('createEndpoint', User::class)) {
            throw new AuthorizationException('User cannot create more endpoints');
        }
    }

    #[\Override]
    public function ensureStubSizeWithinLimits(Authorizable $user, Stub $stub): void
    {
        $size = mb_strlen($stub->toJson(), '8bit');

        if (! $user->can('createStubOfSize', [User::class, $size])) {
            throw new AuthorizationException('Stub size exceeds allowed limit');
        }
    }

    #[\Override]
    public function ensureInputRepeatWithinLimit(Authorizable $user, Input ...$inputs): void
    {
        $maxRepeat = $this->calculateMaxRepeat(...$inputs);

        if (! $user->can('createStubWithRepeat', [User::class, $maxRepeat])) {
            throw new AuthorizationException('Stub object repeat exceeds allowed limit');
        }
    }

    private function calculateMaxRepeat(Input ...$inputs): int
    {
        return $this->inputRepeatMapper->max(...$inputs);
    }
}
