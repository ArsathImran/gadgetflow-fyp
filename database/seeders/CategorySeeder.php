<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Smartphones',
                'description' => 'Latest smartphones for travel, events, and short-term use.',
                'status' => 'active',
            ],
            [
                'name' => 'Laptops',
                'description' => 'Portable laptops for business, study, and creative work.',
                'status' => 'active',
            ],
            [
                'name' => 'Cameras',
                'description' => 'Photography and video gear for content and events.',
                'status' => 'active',
            ],
            [
                'name' => 'Headphones',
                'description' => 'Wireless and studio headphones for work and entertainment.',
                'status' => 'active',
            ],
            [
                'name' => 'Gaming Consoles',
                'description' => 'Gaming devices for parties, vacations, and weekend rentals.',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
