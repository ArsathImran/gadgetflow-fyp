<?php

namespace Database\Factories;

use App\Models\Gadget;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    protected $model = Rental::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 day', '+5 days');
        $endDate = (clone $startDate)->modify('+2 days');

        return [
            'user_id' => User::factory(),
            'gadget_id' => Gadget::factory(),
            'rental_type' => 'day',
            'rental_hours' => null,
            'pickup_type' => 'walk_in',
            'delivery_address' => null,
            'phone_number' => null,
            'ic_number' => null,
            'agreement_accepted' => true,
            'payment_proof' => null,
            'payment_proofs' => null,
            'payment_note' => null,
            'payment_status' => 'not_required',
            'shipping_status' => 'not_applicable',
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_amount' => fake()->randomFloat(2, 50, 500),
            'deposit_amount' => fake()->randomFloat(2, 50, 400),
            'status' => 'pending',
            'returned_at' => null,
            'condition_on_return' => null,
            'return_notes' => null,
            'deposit_status' => 'held',
            'deposit_refund_amount' => null,
            'deposit_deduction_reason' => null,
            'late_fee_amount' => 0,
            'late_fee_waived' => false,
            'payment_collected_at' => null,
            'payment_collected_by' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'payment_status' => 'pending_collection',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'returned_at' => now(),
            'deposit_status' => 'refunded',
            'deposit_refund_amount' => $attributes['deposit_amount'] ?? 0,
            'payment_status' => 'collected',
        ]);
    }
}
