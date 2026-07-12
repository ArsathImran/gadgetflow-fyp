<?php

namespace Database\Seeders;

use App\Models\Bundle;
use Illuminate\Database\Seeder;

class BundleDemoSeeder extends Seeder
{
    public function run(): void
    {
        $bundles = [
            [
                'name' => 'Wedding Photography Essentials',
                'type' => 'wedding',
                'description' => 'Sony A7 IV camera, 24-70mm lens, on-camera flash, carbon tripod, spare battery kit',
                'daily_rental_price' => 280.00,
                'hourly_rental_price' => 38.00,
                'deposit_amount' => 450.00,
                'late_fee_per_day' => 22.00,
            ],
            [
                'name' => 'Wedding Cinematic Package',
                'type' => 'wedding',
                'description' => 'Cinema mirrorless camera, 35mm prime lens, motorized gimbal, shotgun mic, V-mount battery set',
                'daily_rental_price' => 390.00,
                'hourly_rental_price' => 48.00,
                'deposit_amount' => 600.00,
                'late_fee_per_day' => 30.00,
            ],
            [
                'name' => 'Bridal Prep Shoot Kit',
                'type' => 'wedding',
                'description' => 'Compact mirrorless camera, 50mm portrait lens, LED light wand, mini tripod, wireless audio recorder',
                'daily_rental_price' => 220.00,
                'hourly_rental_price' => 30.00,
                'deposit_amount' => 320.00,
                'late_fee_per_day' => 15.00,
            ],
            [
                'name' => 'Golden Hour Portrait Combo',
                'type' => 'wedding',
                'description' => 'Full-frame camera body, 85mm portrait lens, reflector kit, light stand, portable strobe',
                'daily_rental_price' => 310.00,
                'hourly_rental_price' => 42.00,
                'deposit_amount' => 500.00,
                'late_fee_per_day' => 24.00,
            ],
            [
                'name' => 'Ceremony Coverage Duo Set',
                'type' => 'wedding',
                'description' => '2x mirrorless cameras, 24-70mm zoom, 70-200mm telephoto, dual charger, monopod',
                'daily_rental_price' => 400.00,
                'hourly_rental_price' => 50.00,
                'deposit_amount' => 580.00,
                'late_fee_per_day' => 28.00,
            ],
            [
                'name' => 'Reception Highlight Reel Pack',
                'type' => 'wedding',
                'description' => 'Vlog camera, ultra-wide lens, handheld gimbal, LED panel pair, wireless mic set',
                'daily_rental_price' => 260.00,
                'hourly_rental_price' => 34.00,
                'deposit_amount' => 360.00,
                'late_fee_per_day' => 18.00,
            ],
            [
                'name' => 'Intimate Solemnization Kit',
                'type' => 'wedding',
                'description' => 'Mirrorless camera, standard zoom lens, lavalier mic set, compact light panel, travel tripod',
                'daily_rental_price' => 180.00,
                'hourly_rental_price' => 24.00,
                'deposit_amount' => 250.00,
                'late_fee_per_day' => 12.00,
            ],
            [
                'name' => 'Luxury Ballroom Wedding Set',
                'type' => 'wedding',
                'description' => 'Dual-camera setup, fast prime lens kit, gimbal stabilizer, flash trigger set, backup audio recorder',
                'daily_rental_price' => 370.00,
                'hourly_rental_price' => 46.00,
                'deposit_amount' => 560.00,
                'late_fee_per_day' => 27.00,
            ],
            [
                'name' => 'Outdoor Nikah Storytelling Combo',
                'type' => 'wedding',
                'description' => 'Hybrid camera, 16-35mm lens, ND filter set, drone, wireless mic duo',
                'daily_rental_price' => 340.00,
                'hourly_rental_price' => 44.00,
                'deposit_amount' => 540.00,
                'late_fee_per_day' => 26.00,
            ],
            [
                'name' => 'Same Day Edit Wedding Bundle',
                'type' => 'wedding',
                'description' => 'MacBook editing laptop, card reader kit, external SSD, headphones, backup power bank',
                'daily_rental_price' => 240.00,
                'hourly_rental_price' => 32.00,
                'deposit_amount' => 380.00,
                'late_fee_per_day' => 16.00,
            ],
            [
                'name' => 'Indie Filmmaker Kit',
                'type' => 'short_film',
                'description' => 'Cinema camera, 24mm and 50mm prime lenses, cage rig, shotgun mic, fluid head tripod',
                'daily_rental_price' => 360.00,
                'hourly_rental_price' => 45.00,
                'deposit_amount' => 580.00,
                'late_fee_per_day' => 28.00,
            ],
            [
                'name' => 'Run-and-Gun Documentary Combo',
                'type' => 'short_film',
                'description' => 'Compact cinema camera, zoom lens, top handle rig, shotgun mic, field recorder',
                'daily_rental_price' => 290.00,
                'hourly_rental_price' => 37.00,
                'deposit_amount' => 420.00,
                'late_fee_per_day' => 20.00,
            ],
            [
                'name' => 'Studio Interview Setup',
                'type' => 'short_film',
                'description' => 'Mirrorless video camera, 2x LED panels, wireless lav set, backdrop support, teleprompter mount',
                'daily_rental_price' => 250.00,
                'hourly_rental_price' => 32.00,
                'deposit_amount' => 340.00,
                'late_fee_per_day' => 15.00,
            ],
            [
                'name' => 'Drone + Gimbal Package',
                'type' => 'short_film',
                'description' => '4K drone, handheld gimbal, ND filter set, spare batteries, charging hub',
                'daily_rental_price' => 330.00,
                'hourly_rental_price' => 43.00,
                'deposit_amount' => 520.00,
                'late_fee_per_day' => 25.00,
            ],
            [
                'name' => 'Music Video Performance Bundle',
                'type' => 'short_film',
                'description' => 'Full-frame video camera, anamorphic-style lens, RGB tube lights, haze machine trigger, monitor',
                'daily_rental_price' => 400.00,
                'hourly_rental_price' => 50.00,
                'deposit_amount' => 600.00,
                'late_fee_per_day' => 30.00,
            ],
            [
                'name' => 'Short Film Audio Capture Pack',
                'type' => 'short_film',
                'description' => 'Shotgun mic, boom pole, field recorder, wireless lav pair, closed-back monitoring headphones',
                'daily_rental_price' => 170.00,
                'hourly_rental_price' => 22.00,
                'deposit_amount' => 220.00,
                'late_fee_per_day' => 10.00,
            ],
            [
                'name' => 'Night Scene Lighting Combo',
                'type' => 'short_film',
                'description' => '3x bi-color LED panels, softboxes, light stands, extension reel, portable battery pack',
                'daily_rental_price' => 210.00,
                'hourly_rental_price' => 28.00,
                'deposit_amount' => 260.00,
                'late_fee_per_day' => 12.00,
            ],
            [
                'name' => 'Director Monitor Toolkit',
                'type' => 'short_film',
                'description' => 'Wireless video transmitter, director monitor, sun hood, mounting arm, battery kit',
                'daily_rental_price' => 190.00,
                'hourly_rental_price' => 25.00,
                'deposit_amount' => 240.00,
                'late_fee_per_day' => 11.00,
            ],
            [
                'name' => 'Guerrilla Street Shoot Set',
                'type' => 'short_film',
                'description' => 'Compact camera body, pancake lens, handheld stabilizer, on-camera mic, mini LED fill light',
                'daily_rental_price' => 150.00,
                'hourly_rental_price' => 20.00,
                'deposit_amount' => 200.00,
                'late_fee_per_day' => 10.00,
            ],
            [
                'name' => 'Narrative Production Starter Pack',
                'type' => 'short_film',
                'description' => 'Hybrid cinema camera, lens trio, matte box, follow focus, slate and field monitor',
                'daily_rental_price' => 320.00,
                'hourly_rental_price' => 41.00,
                'deposit_amount' => 480.00,
                'late_fee_per_day' => 23.00,
            ],
        ];

        foreach ($bundles as $bundle) {
            Bundle::query()->updateOrCreate(
                ['name' => $bundle['name']],
                [
                    'type' => $bundle['type'],
                    'description' => $bundle['description'],
                    'daily_rental_price' => $bundle['daily_rental_price'],
                    'hourly_rental_price' => $bundle['hourly_rental_price'],
                    'deposit_amount' => $bundle['deposit_amount'],
                    'late_fee_per_day' => $bundle['late_fee_per_day'],
                    'image' => null,
                    'status' => 'active',
                ]
            );
        }
    }
}
