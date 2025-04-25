<?php

namespace Database\Seeders;

use App\Enums\SubscriptionType;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'role_id' => UserRole::SUPER->value,
            'subscription_type' => SubscriptionType::UNLIMITED->value,
            'is_active' => true,
            'name' => 'Super User',
            'email' => 'super@example.com',
            'password' => Hash::make('password'),
        ]);
        User::factory()->create([
            'role_id' => UserRole::ADMIN->value,
            'subscription_type' => SubscriptionType::UNLIMITED->value,
            'is_active' => true,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
