<?php

namespace App\Providers;

use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint;
use App\Policies\EndpointPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Endpoint::class => EndpointPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
