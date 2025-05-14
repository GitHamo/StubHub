<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Eloquent\Endpoint;
use App\Models\Eloquent\EndpointHit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Eloquent\EndpointHit>
 */
final class EndpointHitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<EndpointHit>
     */
    protected $model = EndpointHit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(),
            'endpoint_id' => Endpoint::factory(),
            'signature' => fake()->unique()->md5(),
        ];
    }
}
