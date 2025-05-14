<?php

declare(strict_types=1);

namespace App\Modules\Content;

use App\Facades\DataContextFacade;
use App\Modules\Content\Domain\DataContext;
use App\Modules\Content\Domain\StubGenerator;
use App\Modules\Content\Domain\StubStorage;
use App\Modules\Content\Infrastructure\ContentFaker;
use App\Modules\Content\Infrastructure\ContentGeneratorService;
use App\Modules\Content\Infrastructure\ContentStorageService;
use App\Modules\Content\Infrastructure\DataContextService;
use App\Modules\Content\Infrastructure\EncryptionHelper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class ModuleServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(DataContext::class, DataContextService::class);
        $this->app->bind(StubGenerator::class, ContentGeneratorService::class);
        $this->app->bind(StubStorage::class, ContentStorageService::class);

        /*
         * class dependencies
         */
        $this->app->bind(ContentFaker::class, fn (): ContentFaker => new ContentFaker(
            \Faker\Factory::create(),
            DataContextFacade::flatMap(),
        ));

        $this->app->bind(EncryptionHelper::class, fn (Application $application): EncryptionHelper => new EncryptionHelper(
            $this->getAppSecretKey(),
        ));
    }

    private function getAppSecretKey(): string
    {
        /** @var string */
        $secretKey = Config::get('app.key', '');
        $decodedKey = base64_decode(explode(':', $secretKey)[1] ?? $secretKey);

        if (empty($decodedKey)) {
            throw new RuntimeException('APP_KEY is missing for encryption');
        }

        return $decodedKey;
    }
}
