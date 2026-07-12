<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gadget>
 */
class GadgetFactory extends Factory
{
    protected $model = Gadget::class;

    public function definition(): array
    {
        $brand = fake()->randomElement(['Sony', 'Canon', 'Apple', 'Samsung', 'Dell', 'Nintendo']);
        $model = strtoupper(fake()->bothify('??-###'));

        return [
            'category_id' => Category::factory(),
            'brand' => $brand,
            'model' => $model,
            'name' => $brand . ' ' . $model,
            'description' => fake()->sentence(12),
            'daily_rental_price' => fake()->randomFloat(2, 40, 250),
            'hourly_rental_price' => fake()->randomFloat(2, 8, 40),
            'deposit_amount' => fake()->randomFloat(2, 100, 800),
            'late_fee_per_day' => fake()->randomFloat(2, 5, 50),
            'quantity' => fake()->numberBetween(1, 10),
            'image' => null,
            'gallery_images' => null,
            'status' => 'active',
            'condition' => fake()->randomElement(['good', 'like_new', 'fair']),
        ];
    }
}
