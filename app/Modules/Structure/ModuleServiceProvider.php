<?php

declare(strict_types=1);

namespace App\Modules\Structure;

use App\Modules\Structure\Domain\InputMapper;
use App\Modules\Structure\Infrastructure\StructureInputMapper;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->bind(InputMapper::class, StructureInputMapper::class);
    }
}
