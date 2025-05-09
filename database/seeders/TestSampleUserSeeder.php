<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SubscriptionType;
use App\Enums\UserRole;
use App\Models\Endpoint;
use App\Models\EndpointHit;
use App\Models\StubContent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class TestSampleUserSeeder extends Seeder
{
    private const string TEST_USER_EMAIL = 'test@example.com';
    private const string TEST_USER_PASSWORD = 'password';
    private const int ENDPOINTS_COUNT = 5;
    private const int SIGNATURES_COUNT = 10;
    private const int HITS_COUNT_MIN = 0;
    private const int HITS_COUNT_MAX = 100;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->line('Seeding test sample user.');

        $user = User::factory()->create([
            'role_id' => UserRole::SUPER->value,
            'subscription_type' => SubscriptionType::UNLIMITED->value,
            'name' => 'Test User',
            'email' => self::TEST_USER_EMAIL,
            'password' => Hash::make(self::TEST_USER_PASSWORD),
        ]);

        $this->command->line('Seeding test user endpoints.');

        $endpoints = Endpoint::factory(self::ENDPOINTS_COUNT)->for($user)->create();
        $endpointIds = $endpoints->pluck('id')->toArray();
        $paths = $endpoints->pluck('path')->toArray();

        $this->command->line('Seeding test user endpoint stubs.');

        // create stub files
        $stubPath = storage_path('app/private/stubs');

        if (!File::exists($stubPath)) {
            File::makeDirectory($stubPath, 0755, true);

            $this->command->info("Created directory: $stubPath");
        }

        foreach ($paths as $path) {
            $stubName = hash_hmac('sha256', $path, Config::get('app.key'));
            $stubContent = [
                "message" => "This is stub file $path.",
                "created_at" => now(),
            ];
            StubContent::create([
                'name' => $stubName,
                'content' => json_encode($stubContent, JSON_PRETTY_PRINT),
            ]);
        }

        $this->command->line('Seeding test user endpoint hits.');

        // create hits
        $signatures = array_map(fn () => fake()->md5(), range(1, self::SIGNATURES_COUNT));

        foreach (range(self::HITS_COUNT_MIN, self::HITS_COUNT_MAX) as $i) {
            EndpointHit::create([
                'endpoint_id' => fake()->randomElement($endpointIds),
                'signature' => fake()->randomElement($signatures),
                'created_at'  => now()->subMinutes(rand(0, 1000)),
            ]);
        }

        $this->command->info('Single user sample data seed completed: users, endpoints, stub files, and hits created.');
    }
}
