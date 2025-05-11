<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Endpoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Endpoint>
 */
final class EndpointFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Endpoint>
     */
    protected $model = Endpoint::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->uuid(),
            'user_id' =>  User::factory(),
            'name' => fake()->company(),
            'path' => bin2hex(random_bytes(20)),
            'inputs' => json_encode([fake()->word(), fake()->word()]),
        ];
    }
}
