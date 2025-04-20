<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint;
use App\Modules\Hits\Infrastructure\Persistence\Eloquent\Hit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Hits\Infrastructure\Persistence\Eloquent\Hit>
 */
class HitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Hit::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(),
            'endpoint_id' => Endpoint::factory(),
            'signature' => fake()->unique()->asciify('********************'),
        ];
    }
}
