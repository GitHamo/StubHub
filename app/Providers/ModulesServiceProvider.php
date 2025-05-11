<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Constraints\Domain\ConstraintsCheck;
use App\Modules\Constraints\Infrastructure\ConstraintsCheckService;
use App\Modules\Content\Domain\Generator;
use App\Modules\Content\Domain\Storage;
use App\Modules\Content\Infrastructure\ContentFaker;
use App\Modules\Content\Infrastructure\ContentGeneratorService;
use App\Modules\Content\Infrastructure\ContentStorageService;
use App\Modules\Content\Infrastructure\EncryptionHelper;
use App\Modules\Structure\Domain\InputMapper;
use App\Modules\Structure\Infrastructure\StructureInputMapper;
use App\Repositories\EndpointHitRepository;
use App\Repositories\EndpointRepository;
use App\Repositories\StubContentRepository;
use App\Repositories\Eloquent\EndpointHitRepository as EloquentEndpointHitRepository;
use App\Repositories\Eloquent\EndpointRepository as EloquentEndpointRepository;
use App\Repositories\Eloquent\StubContentRepository as EloquentStubContentRepository;
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
        $this->app->bind(ConstraintsCheck::class, ConstraintsCheckService::class);
        $this->app->bind(Generator::class, ContentGeneratorService::class);
        $this->app->bind(Storage::class, ContentStorageService::class);
        $this->app->bind(InputMapper::class, StructureInputMapper::class);
        /**
         * class dependencies
         */
        $this->app->bind(ContentFaker::class, fn (): ContentFaker => new ContentFaker(
            \Faker\Factory::create(),
            StubFieldContextMapper::flatMap(),
        ));

        $this->app->bind(EncryptionHelper::class, fn (Application $application): EncryptionHelper => new EncryptionHelper(
            $this->getAppSecretKey(),
        ));

        /**
         * Repositories
         */
        $this->app->bind(EndpointHitRepository::class, EloquentEndpointHitRepository::class);
        $this->app->bind(EndpointRepository::class, EloquentEndpointRepository::class);
        $this->app->bind(StubContentRepository::class, EloquentStubContentRepository::class);
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
