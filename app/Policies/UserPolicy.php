<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function createEndpoint(User $user): bool
    {
        if($user->role === UserRole::SUPER) {
            return true;
        }

        $total = $user->endpoints()->count();
        $constraints = $user->subscription_type->constraints();

        return $total < $constraints->maxEndpointsTotal();
    }
}
