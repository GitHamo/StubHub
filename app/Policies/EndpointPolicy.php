<?php

namespace App\Policies;

use App\Models\Eloquent\Endpoint;
use App\Models\User;

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

    public function download(User $user, Endpoint $model): bool
    {
        return $model->user_id === $user->id;
    }

    public function regenerate(User $user, Endpoint $model): bool
    {
        return $model->user_id === $user->id;
    }
}
