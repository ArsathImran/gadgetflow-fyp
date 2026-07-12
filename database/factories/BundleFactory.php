<?php

namespace Database\Factories;

use App\Models\Bundle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bundle>
 */
class BundleFactory extends Factory
{
    protected $model = Bundle::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['wedding', 'short_film']);

        return [
            'name' => fake()->unique()->words(3, true),
            'type' => $type,
            'description' => fake()->sentence(),
            'daily_rental_price' => fake()->numberBetween(150, 400),
            'hourly_rental_price' => fake()->numberBetween(20, 50),
            'deposit_amount' => fake()->numberBetween(200, 600),
            'late_fee_per_day' => fake()->numberBetween(10, 30),
            'image' => null,
            'status' => 'active',
        ];
    }

    public function wedding(): static
    {
        return $this->state(fn () => ['type' => 'wedding']);
    }

    public function shortFilm(): static
    {
        return $this->state(fn () => ['type' => 'short_film']);
    }
}
