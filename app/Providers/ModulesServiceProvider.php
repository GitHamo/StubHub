<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Stubs\StubGenerator;
use App\Modules\Stubs\Infrastructure\FakerService;
use App\Modules\Stubs\Infrastructure\StubGenerateService;
use App\Modules\Endpoints\Domain\EndpointRepository;
use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\EndpointEloquentRepository;
use App\Modules\Hits\Domain\HitRepository;
use App\Modules\Hits\Infrastructure\Persistence\Eloquent\HitEloquentRepository;
use App\Modules\StubStorage\Infrastructure\Data\HamReader;
use App\Modules\StubStorage\Infrastructure\Data\HamWriter;
use App\Modules\StubStorage\Infrastructure\FileStorageRepository;
use App\Modules\StubStorage\StorageRepository;
use App\Support\JsonParser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * Bindings
         */
        $this->app->bind(EndpointRepository::class, EndpointEloquentRepository::class);
        $this->app->bind(HitRepository::class, HitEloquentRepository::class);
        $this->app->bind(StubGenerator::class, StubGenerateService::class);
        $this->app->bind(StorageRepository::class, FileStorageRepository::class);
        /**
         * class dependencies
         */
        $this->app->bind(FakerService::class, function (): FakerService {
            return new FakerService(
                \Faker\Factory::create()
            );
        });

        $stubsPath = Storage::disk('local')->path('stubs');

        $this->app->bind(HamReader::class, function () use ($stubsPath): HamReader {
            return new HamReader(
                $stubsPath
            );
        });

        $this->app->bind(HamWriter::class, function () use ($stubsPath): HamWriter {
            return new HamWriter(
                $stubsPath
            );
        });
    
        $this->app->bind(FileStorageRepository::class, function (Application $application): FileStorageRepository {

            $secretKey = Config::get('app.key');
            $decodedKey = base64_decode(explode(':', $secretKey)[1] ?? $secretKey);

            if (empty($decodedKey)) {
                throw new RuntimeException('APP_KEY is missing for encryption');
            }

            return new FileStorageRepository(
                $application->make(HamReader::class),
                $application->make(HamWriter::class),
                $application->make(JsonParser::class),
                $secretKey,
            );
        });
    }
}
