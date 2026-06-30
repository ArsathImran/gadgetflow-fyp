<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Database\Seeder;

class GadgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = Category::query()
            ->whereIn('name', [
                'Smartphones',
                'Laptops',
                'Cameras',
                'Headphones',
                'Gaming Consoles',
            ])
            ->pluck('id', 'name');

        $gadgets = [
            [
                'category_name' => 'Smartphones',
                'name' => 'Vivo X300 Pro',
                'description' => 'Flagship smartphone with advanced camera features, fast charging, and premium performance for daily rentals.',
                'daily_rental_price' => 89.00,
                'hourly_rental_price' => 15.00,
                'deposit_amount' => 300.00,
                'quantity' => 6,
                'image' => 'gadgets/demo/vivo-x300.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Smartphones',
                'name' => 'Samsung Galaxy S25',
                'description' => 'High-end Android phone suited for travel, demos, and event coverage with a sharp display and long battery life.',
                'daily_rental_price' => 95.00,
                'hourly_rental_price' => 16.00,
                'deposit_amount' => 320.00,
                'quantity' => 5,
                'image' => 'gadgets/demo/samsung-galaxy-s25.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Laptops',
                'name' => 'MacBook Pro 14 M4',
                'description' => 'Powerful laptop for editing, presentations, and professional work on short-term rental schedules.',
                'daily_rental_price' => 180.00,
                'hourly_rental_price' => 30.00,
                'deposit_amount' => 600.00,
                'quantity' => 4,
                'image' => 'gadgets/demo/macbook-pro-14-m4.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Laptops',
                'name' => 'Dell XPS 13',
                'description' => 'Compact Windows ultrabook with crisp display and strong battery life for meetings and remote work.',
                'daily_rental_price' => 140.00,
                'hourly_rental_price' => 24.00,
                'deposit_amount' => 450.00,
                'quantity' => 5,
                'image' => 'gadgets/demo/dell-xps-13.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Cameras',
                'name' => 'Canon EOS R6 Mark II',
                'description' => 'Mirrorless camera setup for professional shoots, livestreams, and event photography.',
                'daily_rental_price' => 210.00,
                'hourly_rental_price' => 35.00,
                'deposit_amount' => 700.00,
                'quantity' => 3,
                'image' => 'gadgets/demo/canon-eos-r6-mark-ii.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Cameras',
                'name' => 'Sony ZV-E1',
                'description' => 'Creator-focused camera with excellent autofocus and compact portability for video-first projects.',
                'daily_rental_price' => 195.00,
                'hourly_rental_price' => 32.00,
                'deposit_amount' => 650.00,
                'quantity' => 4,
                'image' => 'gadgets/demo/sony-zv-e1.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Headphones',
                'name' => 'Sony WH-1000XM6',
                'description' => 'Premium noise-cancelling headphones for flights, remote work, and studio listening.',
                'daily_rental_price' => 45.00,
                'hourly_rental_price' => 8.00,
                'deposit_amount' => 180.00,
                'quantity' => 8,
                'image' => 'gadgets/demo/sony-wh1000xm6.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Headphones',
                'name' => 'AirPods Max',
                'description' => 'High-end wireless headphones with spatial audio and premium comfort for lifestyle rentals.',
                'daily_rental_price' => 55.00,
                'hourly_rental_price' => 9.00,
                'deposit_amount' => 220.00,
                'quantity' => 6,
                'image' => 'gadgets/demo/airpods-max.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Gaming Consoles',
                'name' => 'PlayStation 5 Slim',
                'description' => 'Modern console package for weekend gaming sessions, events, and entertainment setups.',
                'daily_rental_price' => 120.00,
                'hourly_rental_price' => 20.00,
                'deposit_amount' => 400.00,
                'quantity' => 4,
                'image' => 'gadgets/demo/playstation-5-slim.jpg',
                'status' => 'active',
            ],
            [
                'category_name' => 'Gaming Consoles',
                'name' => 'Nintendo Switch OLED',
                'description' => 'Portable gaming console ideal for family trips, parties, and flexible indoor or outdoor play.',
                'daily_rental_price' => 70.00,
                'hourly_rental_price' => 12.00,
                'deposit_amount' => 250.00,
                'quantity' => 7,
                'image' => 'gadgets/demo/nintendo-switch-oled.jpg',
                'status' => 'active',
            ],
        ];

        foreach ($gadgets as $gadget) {
            $categoryId = $categoryIds->get($gadget['category_name']);

            if (! $categoryId) {
                continue;
            }

            unset($gadget['category_name']);

            Gadget::updateOrCreate(
                ['name' => $gadget['name']],
                [
                    'category_id' => $categoryId,
                    'description' => $gadget['description'],
                    'daily_rental_price' => $gadget['daily_rental_price'],
                    'hourly_rental_price' => $gadget['hourly_rental_price'],
                    'deposit_amount' => $gadget['deposit_amount'],
                    'quantity' => $gadget['quantity'],
                    'image' => $gadget['image'],
                    'status' => $gadget['status'],
                ]
            );
        }
    }
}
