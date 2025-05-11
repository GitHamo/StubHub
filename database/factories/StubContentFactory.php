<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StubContent>
 */
final class StubContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        $data = [
            'name' => fake()->fullName(),
            'email' => fake()->unique()->safeEmail,
            'address' => [
                'street' => fake()->streetAddress(),
                'city' => fake()->city(),
                'country' => fake()->country(),
            ],
            'created_at' => fake()->iso8601(),
        ];

        $content = json_encode($data, JSON_PRETTY_PRINT);

        return [
            'id' => fake()->unique()->randomNumber(),
            'filename' => fake()->sha256(),
            'content' => $content,
        ];
    }
}
