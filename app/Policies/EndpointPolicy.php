<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint;

class EndpointPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function deleteEndpoint(User $user, Endpoint $model): bool
    {
        return $model->user_id === $user->id;
    }
}
