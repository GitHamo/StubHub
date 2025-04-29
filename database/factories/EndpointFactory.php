<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint>
 */
class EndpointFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Endpoint::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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
