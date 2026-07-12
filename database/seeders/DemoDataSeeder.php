<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gadget;
use App\Models\Rental;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            GadgetSeeder::class,
        ]);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@gadgetflow.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_blocked' => false,
                'email_verified_at' => now(),
            ]
        );

        $customers = collect([
            ['name' => 'Aisyah Rahman', 'email' => 'aisyah@gadgetflow.test'],
            ['name' => 'Daniel Lee', 'email' => 'daniel@gadgetflow.test'],
            ['name' => 'Farah Zulkifli', 'email' => 'farah@gadgetflow.test'],
            ['name' => 'Jason Tan', 'email' => 'jason@gadgetflow.test'],
            ['name' => 'Nadia Ismail', 'email' => 'nadia@gadgetflow.test'],
        ])->map(function (array $customer) {
            return User::factory()->customer()->createOne($customer);
        });

        $categories = Category::query()->pluck('id', 'name');

        $gadgetSpecs = [
            ['category' => 'Smartphones', 'brand' => 'Apple', 'model' => 'iPhone 15 Pro', 'name' => 'iPhone 15 Pro', 'description' => 'Flagship phone for events, demos, and travel content capture.', 'daily_rental_price' => 110.00, 'hourly_rental_price' => 18.00, 'deposit_amount' => 400.00, 'late_fee_per_day' => 35.00, 'base_quantity' => 6, 'status' => 'active', 'condition' => 'like_new'],
            ['category' => 'Smartphones', 'brand' => 'Samsung', 'model' => 'Galaxy S25', 'name' => 'Samsung Galaxy S25', 'description' => 'High-end Android device with excellent battery life and camera performance.', 'daily_rental_price' => 95.00, 'hourly_rental_price' => 16.00, 'deposit_amount' => 320.00, 'late_fee_per_day' => 28.00, 'base_quantity' => 5, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Smartphones', 'brand' => 'Google', 'model' => 'Pixel 9 Pro', 'name' => 'Google Pixel 9 Pro', 'description' => 'Ideal for photo-focused users who want a clean Android experience.', 'daily_rental_price' => 92.00, 'hourly_rental_price' => 15.00, 'deposit_amount' => 300.00, 'late_fee_per_day' => 25.00, 'base_quantity' => 3, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Laptops', 'brand' => 'Apple', 'model' => 'MacBook Pro 14 M4', 'name' => 'MacBook Pro 14 M4', 'description' => 'Powerful creative workstation for editing, design, and presentations.', 'daily_rental_price' => 180.00, 'hourly_rental_price' => 30.00, 'deposit_amount' => 600.00, 'late_fee_per_day' => 45.00, 'base_quantity' => 4, 'status' => 'active', 'condition' => 'like_new'],
            ['category' => 'Laptops', 'brand' => 'Dell', 'model' => 'XPS 13', 'name' => 'Dell XPS 13', 'description' => 'Compact premium ultrabook for meetings, coursework, and remote work.', 'daily_rental_price' => 140.00, 'hourly_rental_price' => 24.00, 'deposit_amount' => 450.00, 'late_fee_per_day' => 32.00, 'base_quantity' => 5, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Laptops', 'brand' => 'Lenovo', 'model' => 'ThinkPad X1 Carbon', 'name' => 'ThinkPad X1 Carbon', 'description' => 'Business-grade laptop with strong keyboard and battery life.', 'daily_rental_price' => 135.00, 'hourly_rental_price' => 22.00, 'deposit_amount' => 420.00, 'late_fee_per_day' => 30.00, 'base_quantity' => 2, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Cameras', 'brand' => 'Canon', 'model' => 'EOS R6 Mark II', 'name' => 'Canon EOS R6 Mark II', 'description' => 'Mirrorless camera for professional shoots and events.', 'daily_rental_price' => 210.00, 'hourly_rental_price' => 35.00, 'deposit_amount' => 700.00, 'late_fee_per_day' => 50.00, 'base_quantity' => 3, 'status' => 'active', 'condition' => 'like_new'],
            ['category' => 'Cameras', 'brand' => 'Sony', 'model' => 'ZV-E1', 'name' => 'Sony ZV-E1', 'description' => 'Creator-focused full-frame camera with strong autofocus.', 'daily_rental_price' => 195.00, 'hourly_rental_price' => 32.00, 'deposit_amount' => 650.00, 'late_fee_per_day' => 48.00, 'base_quantity' => 4, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Cameras', 'brand' => 'DJI', 'model' => 'Osmo Pocket 3', 'name' => 'DJI Osmo Pocket 3', 'description' => 'Compact gimbal camera for vlogging and smooth travel footage.', 'daily_rental_price' => 85.00, 'hourly_rental_price' => 14.00, 'deposit_amount' => 250.00, 'late_fee_per_day' => 20.00, 'base_quantity' => 6, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Headphones', 'brand' => 'Sony', 'model' => 'WH-1000XM6', 'name' => 'Sony WH-1000XM6', 'description' => 'Premium wireless noise-cancelling headphones.', 'daily_rental_price' => 45.00, 'hourly_rental_price' => 8.00, 'deposit_amount' => 180.00, 'late_fee_per_day' => 12.00, 'base_quantity' => 8, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Headphones', 'brand' => 'Apple', 'model' => 'AirPods Max', 'name' => 'AirPods Max', 'description' => 'High-end audio with comfort for travel and editing sessions.', 'daily_rental_price' => 55.00, 'hourly_rental_price' => 9.00, 'deposit_amount' => 220.00, 'late_fee_per_day' => 15.00, 'base_quantity' => 4, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Headphones', 'brand' => 'Bose', 'model' => 'QuietComfort Ultra', 'name' => 'Bose QuietComfort Ultra', 'description' => 'Comfort-first headphones for commuting and calls.', 'daily_rental_price' => 42.00, 'hourly_rental_price' => 7.00, 'deposit_amount' => 170.00, 'late_fee_per_day' => 10.00, 'base_quantity' => 7, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Gaming Consoles', 'brand' => 'Sony', 'model' => 'PlayStation 5 Slim', 'name' => 'PlayStation 5 Slim', 'description' => 'Weekend-ready console package for home entertainment and events.', 'daily_rental_price' => 120.00, 'hourly_rental_price' => 20.00, 'deposit_amount' => 400.00, 'late_fee_per_day' => 30.00, 'base_quantity' => 3, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Gaming Consoles', 'brand' => 'Nintendo', 'model' => 'Switch OLED', 'name' => 'Nintendo Switch OLED', 'description' => 'Portable party-friendly console for trips and family gatherings.', 'daily_rental_price' => 70.00, 'hourly_rental_price' => 12.00, 'deposit_amount' => 250.00, 'late_fee_per_day' => 18.00, 'base_quantity' => 6, 'status' => 'active', 'condition' => 'good'],
            ['category' => 'Gaming Consoles', 'brand' => 'Meta', 'model' => 'Quest 3', 'name' => 'Meta Quest 3', 'description' => 'VR headset bundle for immersive gaming and demos.', 'daily_rental_price' => 115.00, 'hourly_rental_price' => 19.00, 'deposit_amount' => 380.00, 'late_fee_per_day' => 26.00, 'base_quantity' => 2, 'status' => 'active', 'condition' => 'like_new'],
        ];

        $baseQuantities = [];

        $gadgets = collect($gadgetSpecs)->mapWithKeys(function (array $spec) use ($categories, &$baseQuantities) {
            $gadget = Gadget::query()->updateOrCreate(
                ['name' => $spec['name']],
                [
                    'category_id' => $categories[$spec['category']],
                    'brand' => $spec['brand'],
                    'model' => $spec['model'],
                    'description' => $spec['description'],
                    'daily_rental_price' => $spec['daily_rental_price'],
                    'hourly_rental_price' => $spec['hourly_rental_price'],
                    'deposit_amount' => $spec['deposit_amount'],
                    'late_fee_per_day' => $spec['late_fee_per_day'],
                    'quantity' => $spec['base_quantity'],
                    'status' => $spec['status'],
                    'condition' => $spec['condition'],
                    'image' => null,
                    'gallery_images' => null,
                ]
            );

            $baseQuantities[$gadget->id] = $spec['base_quantity'];

            return [$spec['name'] => $gadget];
        });

        $now = Carbon::now();

        $rentalBlueprints = [
            ['customer' => 'Aisyah Rahman', 'gadget' => 'iPhone 15 Pro', 'status' => 'pending', 'rental_type' => 'day', 'pickup_type' => 'walk_in', 'start_date' => $now->copy()->addDays(2), 'end_date' => $now->copy()->addDays(4), 'total_amount' => 330.00],
            ['customer' => 'Daniel Lee', 'gadget' => 'Sony WH-1000XM6', 'status' => 'pending', 'rental_type' => 'hour', 'rental_hours' => 6, 'pickup_type' => 'delivery', 'delivery_address' => '12 Jalan Kiara, Kuala Lumpur', 'phone_number' => '0123456789', 'ic_number' => '900101-10-1234', 'total_amount' => 48.00],
            ['customer' => 'Farah Zulkifli', 'gadget' => 'DJI Osmo Pocket 3', 'status' => 'pending', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'delivery_address' => '25 Persiaran Bayan, Penang', 'phone_number' => '0178001122', 'ic_number' => '920202-07-4321', 'start_date' => $now->copy()->addDays(5), 'end_date' => $now->copy()->addDays(6), 'total_amount' => 170.00],
            ['customer' => 'Jason Tan', 'gadget' => 'MacBook Pro 14 M4', 'status' => 'approved', 'rental_type' => 'day', 'pickup_type' => 'walk_in', 'payment_status' => 'collected', 'start_date' => $now->copy()->subDay(), 'end_date' => $now->copy()->addDays(2), 'total_amount' => 720.00],
            ['customer' => 'Nadia Ismail', 'gadget' => 'Canon EOS R6 Mark II', 'status' => 'approved', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'payment_status' => 'verified', 'shipping_status' => 'waiting_for_shipping', 'delivery_address' => '8 Lorong Damai, Johor Bahru', 'phone_number' => '0134442211', 'ic_number' => '950505-01-7812', 'payment_proofs' => ['payments/demo-proof-1.jpg'], 'payment_proof' => 'payments/demo-proof-1.jpg', 'payment_note' => 'Bank transfer sent.', 'start_date' => $now->copy()->subDays(2), 'end_date' => $now->copy()->addDay(), 'total_amount' => 840.00],
            ['customer' => 'Aisyah Rahman', 'gadget' => 'PlayStation 5 Slim', 'status' => 'approved', 'rental_type' => 'hour', 'rental_hours' => 8, 'pickup_type' => 'walk_in', 'payment_status' => 'pending_collection', 'start_date' => $now->copy(), 'end_date' => $now->copy()->addDay(), 'total_amount' => 160.00],
            ['customer' => 'Daniel Lee', 'gadget' => 'Meta Quest 3', 'status' => 'approved', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'payment_status' => 'verified', 'shipping_status' => 'shipped', 'delivery_address' => '3 Jalan Melur, Shah Alam', 'phone_number' => '0197654321', 'ic_number' => '910303-10-8765', 'payment_proofs' => ['payments/demo-proof-2.jpg'], 'payment_proof' => 'payments/demo-proof-2.jpg', 'start_date' => $now->copy()->subDays(5), 'end_date' => $now->copy()->subDays(1), 'total_amount' => 575.00],
            ['customer' => 'Farah Zulkifli', 'gadget' => 'ThinkPad X1 Carbon', 'status' => 'approved', 'rental_type' => 'day', 'pickup_type' => 'walk_in', 'payment_status' => 'collected', 'start_date' => $now->copy()->subDays(6), 'end_date' => $now->copy()->subDays(2), 'total_amount' => 675.00],
            ['customer' => 'Jason Tan', 'gadget' => 'iPhone 15 Pro', 'status' => 'approved', 'rental_type' => 'hour', 'rental_hours' => 10, 'pickup_type' => 'delivery', 'payment_status' => 'verified', 'shipping_status' => 'delivered', 'delivery_address' => '19 Jalan Sentosa, Subang Jaya', 'phone_number' => '0162211443', 'ic_number' => '880808-14-1020', 'payment_proofs' => ['payments/demo-proof-3.jpg'], 'payment_proof' => 'payments/demo-proof-3.jpg', 'start_date' => $now->copy()->subDays(4), 'end_date' => $now->copy()->subDays(1), 'total_amount' => 180.00],
            ['customer' => 'Nadia Ismail', 'gadget' => 'Google Pixel 9 Pro', 'status' => 'approved', 'rental_type' => 'day', 'pickup_type' => 'walk_in', 'payment_status' => 'pending_collection', 'start_date' => $now->copy()->subDays(3), 'end_date' => $now->copy()->subDays(1), 'total_amount' => 276.00],
            ['customer' => 'Aisyah Rahman', 'gadget' => 'Dell XPS 13', 'status' => 'completed', 'rental_type' => 'day', 'pickup_type' => 'walk_in', 'payment_status' => 'collected', 'start_date' => $now->copy()->subMonths(2)->addDays(3), 'end_date' => $now->copy()->subMonths(2)->addDays(6), 'returned_at' => $now->copy()->subMonths(2)->addDays(6)->setTime(18, 0), 'created_at' => $now->copy()->subMonths(2)->addDays(1), 'updated_at' => $now->copy()->subMonths(2)->addDays(6), 'total_amount' => 560.00, 'deposit_status' => 'refunded', 'deposit_refund_amount' => 450.00, 'late_fee_amount' => 0.00, 'condition_on_return' => 'good', 'return_notes' => 'Returned clean and on time.'],
            ['customer' => 'Daniel Lee', 'gadget' => 'Sony ZV-E1', 'status' => 'completed', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'payment_status' => 'verified', 'shipping_status' => 'delivered', 'delivery_address' => '41 Jalan Cerdas, Klang', 'phone_number' => '0188899001', 'ic_number' => '890909-10-4433', 'payment_proofs' => ['payments/demo-proof-4.jpg'], 'payment_proof' => 'payments/demo-proof-4.jpg', 'start_date' => $now->copy()->subMonths(2)->addDays(10), 'end_date' => $now->copy()->subMonths(2)->addDays(12), 'returned_at' => $now->copy()->subMonths(2)->addDays(14)->setTime(11, 30), 'created_at' => $now->copy()->subMonths(2)->addDays(8), 'updated_at' => $now->copy()->subMonths(2)->addDays(14), 'total_amount' => 585.00, 'deposit_status' => 'partially_refunded', 'deposit_refund_amount' => 500.00, 'deposit_deduction_reason' => 'Lens cap missing.', 'late_fee_amount' => 96.00, 'condition_on_return' => 'missing_parts', 'return_notes' => 'Late return and missing accessory.'],
            ['customer' => 'Farah Zulkifli', 'gadget' => 'Sony WH-1000XM6', 'status' => 'completed', 'rental_type' => 'hour', 'rental_hours' => 5, 'pickup_type' => 'walk_in', 'payment_status' => 'collected', 'start_date' => $now->copy()->subMonths(2)->addDays(16), 'end_date' => $now->copy()->subMonths(2)->addDays(16), 'returned_at' => $now->copy()->subMonths(2)->addDays(16)->setTime(20, 0), 'created_at' => $now->copy()->subMonths(2)->addDays(16)->setTime(8, 0), 'updated_at' => $now->copy()->subMonths(2)->addDays(16)->setTime(20, 0), 'total_amount' => 40.00, 'deposit_status' => 'refunded', 'deposit_refund_amount' => 180.00, 'late_fee_amount' => 0.00, 'condition_on_return' => 'good', 'return_notes' => 'Smooth same-day rental.'],
            ['customer' => 'Jason Tan', 'gadget' => 'Nintendo Switch OLED', 'status' => 'completed', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'payment_status' => 'verified', 'shipping_status' => 'delivered', 'delivery_address' => '6 Jalan Aman, Ipoh', 'phone_number' => '0145500221', 'ic_number' => '930707-08-5511', 'payment_proofs' => ['payments/demo-proof-5.jpg'], 'payment_proof' => 'payments/demo-proof-5.jpg', 'start_date' => $now->copy()->subMonth()->addDays(2), 'end_date' => $now->copy()->subMonth()->addDays(4), 'returned_at' => $now->copy()->subMonth()->addDays(4)->setTime(19, 15), 'created_at' => $now->copy()->subMonth()->addDay(), 'updated_at' => $now->copy()->subMonth()->addDays(4), 'total_amount' => 210.00, 'deposit_status' => 'refunded', 'deposit_refund_amount' => 250.00, 'late_fee_amount' => 0.00, 'condition_on_return' => 'good', 'return_notes' => 'Family weekend rental.'],
            ['customer' => 'Nadia Ismail', 'gadget' => 'Canon EOS R6 Mark II', 'status' => 'completed', 'rental_type' => 'day', 'pickup_type' => 'walk_in', 'payment_status' => 'collected', 'start_date' => $now->copy()->subMonth()->addDays(7), 'end_date' => $now->copy()->subMonth()->addDays(9), 'returned_at' => $now->copy()->subMonth()->addDays(12)->setTime(10, 0), 'created_at' => $now->copy()->subMonth()->addDays(6), 'updated_at' => $now->copy()->subMonth()->addDays(12), 'total_amount' => 630.00, 'deposit_status' => 'deducted', 'deposit_refund_amount' => 0.00, 'deposit_deduction_reason' => 'Body damage from drop impact.', 'late_fee_amount' => 150.00, 'condition_on_return' => 'damaged', 'return_notes' => 'Visible dent on camera body.'],
            ['customer' => 'Aisyah Rahman', 'gadget' => 'AirPods Max', 'status' => 'completed', 'rental_type' => 'hour', 'rental_hours' => 4, 'pickup_type' => 'walk_in', 'payment_status' => 'collected', 'start_date' => $now->copy()->subMonth()->addDays(13), 'end_date' => $now->copy()->subMonth()->addDays(13), 'returned_at' => $now->copy()->subMonth()->addDays(13)->setTime(17, 45), 'created_at' => $now->copy()->subMonth()->addDays(13)->setTime(9, 30), 'updated_at' => $now->copy()->subMonth()->addDays(13)->setTime(17, 45), 'total_amount' => 36.00, 'deposit_status' => 'refunded', 'deposit_refund_amount' => 220.00, 'late_fee_amount' => 0.00, 'condition_on_return' => 'good', 'return_notes' => 'Used for editing session.'],
            ['customer' => 'Daniel Lee', 'gadget' => 'MacBook Pro 14 M4', 'status' => 'completed', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'payment_status' => 'verified', 'shipping_status' => 'delivered', 'delivery_address' => '27 Jalan Mahkota, Melaka', 'phone_number' => '0112200110', 'ic_number' => '870404-04-1188', 'payment_proofs' => ['payments/demo-proof-6.jpg'], 'payment_proof' => 'payments/demo-proof-6.jpg', 'start_date' => $now->copy()->subMonth()->addDays(18), 'end_date' => $now->copy()->subMonth()->addDays(20), 'returned_at' => $now->copy()->subMonth()->addDays(20)->setTime(21, 0), 'created_at' => $now->copy()->subMonth()->addDays(17), 'updated_at' => $now->copy()->subMonth()->addDays(20), 'total_amount' => 540.00, 'deposit_status' => 'refunded', 'deposit_refund_amount' => 600.00, 'late_fee_amount' => 0.00, 'condition_on_return' => 'good', 'return_notes' => 'Client presentation trip.'],
            ['customer' => 'Farah Zulkifli', 'gadget' => 'Bose QuietComfort Ultra', 'status' => 'completed', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'payment_status' => 'verified', 'shipping_status' => 'delivered', 'delivery_address' => '5 Jalan Puteri, Seremban', 'phone_number' => '0126600778', 'ic_number' => '940606-06-6677', 'payment_proofs' => ['payments/demo-proof-7.jpg'], 'payment_proof' => 'payments/demo-proof-7.jpg', 'start_date' => $now->copy()->subMonth()->addDays(22), 'end_date' => $now->copy()->subMonth()->addDays(24), 'returned_at' => $now->copy()->subMonth()->addDays(25)->setTime(13, 0), 'created_at' => $now->copy()->subMonth()->addDays(21), 'updated_at' => $now->copy()->subMonth()->addDays(25), 'total_amount' => 126.00, 'deposit_status' => 'partially_refunded', 'deposit_refund_amount' => 150.00, 'deposit_deduction_reason' => 'Late return fee offset.', 'late_fee_amount' => 10.00, 'condition_on_return' => 'good', 'return_notes' => 'Returned one day late.'],
            ['customer' => 'Jason Tan', 'gadget' => 'DJI Osmo Pocket 3', 'status' => 'rejected', 'rental_type' => 'day', 'pickup_type' => 'walk_in', 'start_date' => $now->copy()->subMonth()->addDays(8), 'end_date' => $now->copy()->subMonth()->addDays(10), 'created_at' => $now->copy()->subMonth()->addDays(7), 'updated_at' => $now->copy()->subMonth()->addDays(7), 'total_amount' => 255.00],
            ['customer' => 'Nadia Ismail', 'gadget' => 'Meta Quest 3', 'status' => 'rejected', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'delivery_address' => '1 Jalan Bukit, Kota Bharu', 'phone_number' => '0172345678', 'ic_number' => '960909-03-2288', 'created_at' => $now->copy()->subDays(20), 'updated_at' => $now->copy()->subDays(20), 'start_date' => $now->copy()->subDays(18), 'end_date' => $now->copy()->subDays(16), 'total_amount' => 345.00],
            ['customer' => 'Aisyah Rahman', 'gadget' => 'Google Pixel 9 Pro', 'status' => 'cancelled_by_customer', 'rental_type' => 'hour', 'rental_hours' => 3, 'pickup_type' => 'walk_in', 'created_at' => $now->copy()->subDays(14), 'updated_at' => $now->copy()->subDays(13), 'start_date' => $now->copy()->subDays(12), 'end_date' => $now->copy()->subDays(12), 'total_amount' => 45.00],
            ['customer' => 'Daniel Lee', 'gadget' => 'PlayStation 5 Slim', 'status' => 'cancelled_by_customer', 'rental_type' => 'day', 'pickup_type' => 'delivery', 'delivery_address' => '21 Jalan Bestari, Kuching', 'phone_number' => '0181122112', 'ic_number' => '900112-13-4455', 'created_at' => $now->copy()->subDays(11), 'updated_at' => $now->copy()->subDays(10), 'start_date' => $now->copy()->subDays(9), 'end_date' => $now->copy()->subDays(8), 'total_amount' => 240.00],
        ];

        $customerMap = $customers->keyBy('name');
        $completedRentals = collect();

        foreach ($rentalBlueprints as $blueprint) {
            $gadget = $gadgets[$blueprint['gadget']];
            $customer = $customerMap[$blueprint['customer']];
            $startDate = ($blueprint['start_date'] ?? $now)->copy()->startOfDay();
            $endDate = ($blueprint['end_date'] ?? $startDate)->copy()->startOfDay();
            $isDelivery = ($blueprint['pickup_type'] ?? 'walk_in') === 'delivery';
            $status = $blueprint['status'];
            $createdAt = ($blueprint['created_at'] ?? $startDate->copy()->subDays(2))->copy();
            $updatedAt = ($blueprint['updated_at'] ?? $createdAt)->copy();
            $depositStatus = $blueprint['deposit_status'] ?? ($status === 'completed' ? 'refunded' : 'held');
            $depositRefundAmount = array_key_exists('deposit_refund_amount', $blueprint)
                ? $blueprint['deposit_refund_amount']
                : ($status === 'completed' ? (float) $gadget->deposit_amount : null);

            $rental = Rental::query()->create([
                'user_id' => $customer->id,
                'gadget_id' => $gadget->id,
                'rental_type' => $blueprint['rental_type'],
                'rental_hours' => $blueprint['rental_hours'] ?? null,
                'pickup_type' => $blueprint['pickup_type'] ?? 'walk_in',
                'delivery_address' => $isDelivery ? ($blueprint['delivery_address'] ?? 'Demo delivery address') : null,
                'phone_number' => $isDelivery ? ($blueprint['phone_number'] ?? '0123456789') : null,
                'ic_number' => $isDelivery ? ($blueprint['ic_number'] ?? '900101-10-1234') : null,
                'agreement_accepted' => true,
                'payment_proof' => $blueprint['payment_proof'] ?? null,
                'payment_proofs' => $blueprint['payment_proofs'] ?? null,
                'payment_note' => $blueprint['payment_note'] ?? null,
                'payment_status' => $blueprint['payment_status'] ?? ($status === 'approved'
                    ? ($isDelivery ? 'verified' : 'pending_collection')
                    : 'not_required'),
                'shipping_status' => $blueprint['shipping_status'] ?? 'not_applicable',
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_amount' => $blueprint['total_amount'],
                'deposit_amount' => $gadget->deposit_amount,
                'status' => $status,
                'returned_at' => isset($blueprint['returned_at']) ? $blueprint['returned_at']->copy() : null,
                'condition_on_return' => $blueprint['condition_on_return'] ?? null,
                'return_notes' => $blueprint['return_notes'] ?? null,
                'deposit_status' => $depositStatus,
                'deposit_refund_amount' => $depositRefundAmount,
                'deposit_deduction_reason' => $blueprint['deposit_deduction_reason'] ?? null,
                'late_fee_amount' => $blueprint['late_fee_amount'] ?? 0,
                'late_fee_waived' => $blueprint['late_fee_waived'] ?? false,
                'payment_collected_at' => ($blueprint['payment_status'] ?? null) === 'collected' ? $createdAt->copy()->addHours(2) : null,
                'payment_collected_by' => ($blueprint['payment_status'] ?? null) === 'collected' ? $admin->id : null,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            if ($status === 'completed') {
                $completedRentals->push($rental);
            }
        }

        $reviewComments = [
            ['rating' => 5, 'comment' => 'Very smooth rental and the gadget arrived in great condition.'],
            ['rating' => 4, 'comment' => 'Good experience overall, would rent this again.'],
            ['rating' => 5, 'comment' => 'Exactly what I needed for my event.'],
            ['rating' => 3, 'comment' => 'Worked fine, but pickup took a bit longer than expected.'],
            ['rating' => 4, 'comment' => 'Reliable gadget and clear communication from the team.'],
        ];

        $completedRentals->take(5)->values()->each(function (Rental $rental, int $index) use ($reviewComments) {
            $review = $reviewComments[$index];

            Review::query()->create([
                'rental_id' => $rental->id,
                'user_id' => $rental->user_id,
                'gadget_id' => $rental->gadget_id,
                'rating' => $review['rating'],
                'comment' => $review['comment'],
                'created_at' => $rental->returned_at ?? now(),
                'updated_at' => $rental->returned_at ?? now(),
            ]);
        });

        $this->syncAvailableStock($gadgets, $baseQuantities);
    }

    private function syncAvailableStock(Collection $gadgets, array $baseQuantities): void
    {
        $gadgets->each(function (Gadget $gadget) use ($baseQuantities) {
            $approvedCount = Rental::query()
                ->where('gadget_id', $gadget->id)
                ->where('status', 'approved')
                ->count();

            $availableQuantity = max(0, ((int) ($baseQuantities[$gadget->id] ?? $gadget->quantity)) - $approvedCount);

            $gadget->update([
                'quantity' => $availableQuantity,
            ]);
        });
    }
}
