<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\EndpointEloquentRepository;
use App\Modules\Hits\Domain\HitRepository;
use App\Modules\Hits\Infrastructure\Persistence\Eloquent\HitEloquentRepository;
use App\Modules\StubGenerate\Infrastructure\FakerStubMapper;
use App\Modules\StubGenerate\Infrastructure\StubGenerateService;
use App\Modules\StubGenerate\StubGenerator;
use App\Modules\StubStorage\Infrastructure\EloquentStorageRepository;
use App\Modules\StubStorage\Infrastructure\Persistence\Eloquent\StubContentEloquentRepository;
use App\Modules\StubStorage\StorageRepository;
use App\Support\JsonParser;
use App\Support\StubFieldContextMapper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        /**
         * Bindings
         */
        $this->app->bind(EndpointRepository::class, EndpointEloquentRepository::class);
        $this->app->bind(HitRepository::class, HitEloquentRepository::class);
        $this->app->bind(StubGenerator::class, StubGenerateService::class);
        $this->app->bind(StorageRepository::class, EloquentStorageRepository::class);
        /**
         * class dependencies
         */
        $this->app->bind(FakerStubMapper::class, fn(): FakerStubMapper => new FakerStubMapper(
            \Faker\Factory::create(),
            StubFieldContextMapper::flatMap(),
        ));

        $this->app->bind(EloquentStorageRepository::class, fn(Application $application): EloquentStorageRepository => new EloquentStorageRepository(
            $application->make(StubContentEloquentRepository::class),
            $application->make(JsonParser::class),
            $this->getAppSecretKey(),
        ));
    }

    private function getAppSecretKey(): string
    {
        /**
         * @var string
         */
        $secretKey = Config::get('app.key', '');
        $decodedKey = base64_decode(explode(':', $secretKey)[1] ?? $secretKey);

        if (empty($decodedKey)) {
            throw new RuntimeException('APP_KEY is missing for encryption');
        }

        return $decodedKey;
    }
}
