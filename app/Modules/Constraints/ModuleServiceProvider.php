<?php

declare(strict_types=1);

namespace App\Modules\Constraints;

use App\Modules\Constraints\Domain\ConstraintsCheck;
use App\Modules\Constraints\Infrastructure\ConstraintsCheckService;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->bind(ConstraintsCheck::class, ConstraintsCheckService::class);
    }
}
