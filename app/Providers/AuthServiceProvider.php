<?php

namespace App\Providers;

use App\Models\User;
use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint;
use App\Policies\EndpointPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Endpoint::class => EndpointPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
