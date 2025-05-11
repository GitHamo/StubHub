<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Data\SystemConstraints;
use App\Models\Domain\Endpoint;
use App\Models\User as UserModel;

class UserPolicy
{
    public function createEndpoint(UserModel $user): bool
    {
        if ($user->getRole() === UserRole::SUPER) {
            return true;
        }

        $total = $user->getEndpointsCount();
        $maxEndpointsTotal = $this->getConstraints($user)->maxEndpointsTotal();

        return $total < $maxEndpointsTotal;
    }

    public function deleteEndpoint(UserModel $user, Endpoint $model): bool
    {
        if ($user->getRole() === UserRole::SUPER) {
            return true;
        }

        return $model->userId() === $user->getId();
    }

    public function createStubOfSize(UserModel $user, int $size): bool
    {
        if ($user->getRole() === UserRole::SUPER) {
            return true;
        }

        $maxStubSize = $this->getConstraints($user)->maxStubSize();

        return $size <= $maxStubSize;
    }

    public function createStubWithRepeat(UserModel $user, int $repeat): bool
    {
        if ($user->getRole() === UserRole::SUPER) {
            return true;
        }

        $maxRepeat = $this->getConstraints($user)->maxObjectRepeat();

        return $repeat <= $maxRepeat;
    }

    private function getConstraints(UserModel $user): SystemConstraints
    {
        return $user->getSubscriptionType()->constraints();
    }
}
