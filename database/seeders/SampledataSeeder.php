<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Eloquent\Endpoint;
use App\Models\Eloquent\EndpointHit;
use App\Models\Eloquent\StubContent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class SampledataSeeder extends Seeder
{
    private const int USERS_COUNT = 10;
    private const int ENDPOINTS_PER_USER_COUNT_MIN = 1;
    private const int ENDPOINTS_PER_USER_COUNT_MAX = 3;
    private const int SIGNATURES_COUNT = 10;
    private const int HITS_COUNT_MIN = 1;
    private const int HITS_COUNT_MAX = 100;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::isProduction()) {
            $this->command->info('SampledataSeeder skipped: not in local environment.');
            return;
        }

        for ($i = 0; $i < self::USERS_COUNT; $i++) {

            // create user

            $user = User::factory()->create([
                'password' => Hash::make('password'),
            ]);

            // create endpoints

            $endpoints = Endpoint::factory()->count(rand(self::ENDPOINTS_PER_USER_COUNT_MIN, self::ENDPOINTS_PER_USER_COUNT_MAX))->for($user)->create();
            $endpointIds = $endpoints->pluck('id')->toArray();
            $paths = $endpoints->pluck('path')->toArray();

            // create stub contents

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

            // create hits

            $signatures = array_map(fn () => fake()->md5(), range(1, self::SIGNATURES_COUNT));

            foreach (range(self::HITS_COUNT_MIN, self::HITS_COUNT_MAX) as $i) {
                EndpointHit::create([
                    'endpoint_id' => fake()->randomElement($endpointIds),
                    'signature' => fake()->randomElement($signatures),
                    'created_at'  => now()->subMinutes(rand(0, 1000)),
                ]);
            }

        }

        $this->command->info('Sample data seed completed: users, endpoints, stub files created, and hits created.');
    }
}
