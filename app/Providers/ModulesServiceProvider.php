<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Eloquent\EndpointHitRepository as EloquentEndpointHitRepository;
use App\Repositories\Eloquent\EndpointRepository as EloquentEndpointRepository;
use App\Repositories\Eloquent\StubContentRepository as EloquentStubContentRepository;
use App\Repositories\EndpointHitRepository;
use App\Repositories\EndpointRepository;
use App\Repositories\StubContentRepository;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->registerModules();
        $this->registerRepositories();
    }

    private function registerRepositories(): void
    {
        $this->app->bind(EndpointHitRepository::class, EloquentEndpointHitRepository::class);
        $this->app->bind(EndpointRepository::class, EloquentEndpointRepository::class);
        $this->app->bind(StubContentRepository::class, EloquentStubContentRepository::class);
    }

    /**
     * Auto-loads module service providers named exactly "ModuleServiceProvider.php".
     * Each must extend Illuminate\Support\ServiceProvider and live in its module root.
     * Example: app/Modules/Blog/ModuleServiceProvider.php => App\Modules\Blog\ModuleServiceProvider.
     */
    private function registerModules(): void
    {
        $modulesPath = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'Modules', '']);
        $modules = array_slice(scandir($modulesPath) ?: [], 2);
        foreach ($modules as $moduleName) {
            $moduleServiceProviderPath = $modulesPath . implode(
                DIRECTORY_SEPARATOR,
                [$moduleName, 'ModuleServiceProvider.php']
            );
            if (file_exists($moduleServiceProviderPath)) {
                $moduleNamespace = str_replace([$modulesPath, '.php', '/'], ['', '', '\\'], $moduleServiceProviderPath);
                $serviceProvider = "App\\Modules\\$moduleNamespace";
                $this->app->register($serviceProvider);
            }
        }
    }
}
