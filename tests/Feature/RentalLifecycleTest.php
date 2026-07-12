<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\Rental;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RentalLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    public function test_customer_can_create_pending_rental_request_with_correct_hourly_total_amount(): void
    {
        $customer = User::factory()->customer()->create();
        $gadget = Gadget::factory()->create([
            'quantity' => 3,
            'status' => 'active',
            'hourly_rental_price' => 12.50,
            'deposit_amount' => 150.00,
        ]);

        $response = $this->actingAs($customer)->post(route('customer.rentals.store'), [
            'gadget_id' => $gadget->id,
            'rental_type' => 'hour',
            'rental_hours' => 4,
            'pickup_type' => 'walk_in',
            'agreement_accepted' => true,
        ]);

        $response->assertRedirect(route('customer.rentals.index'));

        $this->assertDatabaseHas('rentals', [
            'user_id' => $customer->id,
            'gadget_id' => $gadget->id,
            'rental_type' => 'hour',
            'rental_hours' => 4,
            'status' => 'pending',
            'total_amount' => 50.00,
            'deposit_amount' => 150.00,
        ]);
    }

    public function test_customer_can_create_pending_rental_request_with_correct_daily_total_amount(): void
    {
        $customer = User::factory()->customer()->create();
        $gadget = Gadget::factory()->create([
            'quantity' => 2,
            'status' => 'active',
            'daily_rental_price' => 45.00,
            'deposit_amount' => 200.00,
        ]);

        $response = $this->actingAs($customer)->post(route('customer.rentals.store'), [
            'gadget_id' => $gadget->id,
            'rental_type' => 'day',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-12',
            'pickup_type' => 'walk_in',
            'agreement_accepted' => true,
        ]);

        $response->assertRedirect(route('customer.rentals.index'));

        $this->assertDatabaseHas('rentals', [
            'user_id' => $customer->id,
            'gadget_id' => $gadget->id,
            'rental_type' => 'day',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-12',
            'status' => 'pending',
            'total_amount' => 135.00,
            'deposit_amount' => 200.00,
        ]);
    }

    public function test_admin_approving_rental_decrements_gadget_quantity(): void
    {
        $admin = User::factory()->admin()->create();
        $rental = Rental::factory()->create([
            'status' => 'pending',
            'pickup_type' => 'walk_in',
            'payment_status' => 'not_required',
            'gadget_id' => Gadget::factory()->create([
                'quantity' => 2,
                'status' => 'active',
            ])->id,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.rentals.approve', $rental));

        $response->assertRedirect();

        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'status' => 'approved',
            'payment_status' => 'pending_collection',
        ]);

        $this->assertSame(1, $rental->gadget->fresh()->quantity);
    }

    public function test_second_approval_cannot_oversell_stock_below_zero(): void
    {
        $admin = User::factory()->admin()->create();
        $gadget = Gadget::factory()->create([
            'quantity' => 1,
            'status' => 'active',
        ]);

        $firstRental = Rental::factory()->create([
            'gadget_id' => $gadget->id,
            'status' => 'pending',
            'pickup_type' => 'walk_in',
        ]);

        $secondRental = Rental::factory()->create([
            'gadget_id' => $gadget->id,
            'status' => 'pending',
            'pickup_type' => 'walk_in',
        ]);

        $this->actingAs($admin)->patch(route('admin.rentals.approve', $firstRental))
            ->assertRedirect();

        $this->actingAs($admin)->patch(route('admin.rentals.approve', $secondRental))
            ->assertStatus(422);

        $this->assertSame(0, $gadget->fresh()->quantity);
        $this->assertDatabaseHas('rentals', [
            'id' => $firstRental->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('rentals', [
            'id' => $secondRental->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_rejecting_rental_sets_rejected_status_without_decrementing_quantity(): void
    {
        $admin = User::factory()->admin()->create();
        $gadget = Gadget::factory()->create([
            'quantity' => 4,
            'status' => 'active',
        ]);
        $rental = Rental::factory()->create([
            'gadget_id' => $gadget->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.rentals.reject', $rental));

        $response->assertRedirect();

        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'status' => 'rejected',
        ]);
        $this->assertSame(4, $gadget->fresh()->quantity);
    }

    public function test_customer_can_cancel_their_own_pending_rental(): void
    {
        $customer = User::factory()->customer()->create();
        $rental = Rental::factory()->create([
            'user_id' => $customer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($customer)->patch(route('customer.rentals.cancel', $rental));

        $response->assertRedirect(route('customer.rentals.index'));

        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'status' => 'cancelled_by_customer',
        ]);
    }

    public function test_customer_cannot_cancel_an_already_approved_rental(): void
    {
        $customer = User::factory()->customer()->create();
        $rental = Rental::factory()->approved()->create([
            'user_id' => $customer->id,
        ]);

        $response = $this->actingAs($customer)->patch(route('customer.rentals.cancel', $rental));

        $response->assertRedirect(route('customer.rentals.index'));

        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_marking_rental_returned_with_full_refund_on_time_completes_rental_and_restocks_gadget(): void
    {
        $admin = User::factory()->admin()->create();
        $gadget = Gadget::factory()->create([
            'quantity' => 0,
            'late_fee_per_day' => 18.00,
        ]);
        $rental = Rental::factory()->approved()->create([
            'gadget_id' => $gadget->id,
            'deposit_amount' => 300.00,
            'end_date' => '2026-07-12',
        ]);

        $this->travelTo(now()->setDate(2026, 7, 12)->setTime(10, 0));

        $response = $this->actingAs($admin)->patch(route('admin.rentals.return', $rental), [
            'condition_on_return' => 'good',
            'deposit_decision' => 'full_refund',
        ]);

        $response->assertRedirect();

        $this->assertSame(1, $gadget->fresh()->quantity);
        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'status' => 'completed',
            'deposit_status' => 'refunded',
            'deposit_refund_amount' => 300.00,
            'late_fee_amount' => 0.00,
            'late_fee_waived' => false,
            'condition_on_return' => 'good',
        ]);
    }

    public function test_admin_marking_rental_returned_with_partial_refund_applies_late_fee_for_overdue_rental(): void
    {
        $admin = User::factory()->admin()->create();
        $gadget = Gadget::factory()->create([
            'quantity' => 0,
            'late_fee_per_day' => 25.00,
        ]);
        $rental = Rental::factory()->approved()->create([
            'gadget_id' => $gadget->id,
            'deposit_amount' => 400.00,
            'end_date' => '2026-07-10',
        ]);

        $this->travelTo(now()->setDate(2026, 7, 12)->setTime(9, 0));

        $response = $this->actingAs($admin)->patch(route('admin.rentals.return', $rental), [
            'condition_on_return' => 'damaged',
            'return_notes' => 'Minor scratches on the casing.',
            'deposit_decision' => 'partial_refund',
            'deposit_refund_amount' => 250.00,
            'deposit_deduction_reason' => 'Repair and cleaning charges.',
        ]);

        $response->assertRedirect();

        $this->assertSame(1, $gadget->fresh()->quantity);
        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'status' => 'completed',
            'deposit_status' => 'partially_refunded',
            'deposit_refund_amount' => 250.00,
            'deposit_deduction_reason' => 'Repair and cleaning charges.',
            'late_fee_amount' => 50.00,
            'late_fee_waived' => false,
            'condition_on_return' => 'damaged',
        ]);
    }

    public function test_admin_marking_rental_returned_with_deduct_all_sets_zero_refund(): void
    {
        $admin = User::factory()->admin()->create();
        $gadget = Gadget::factory()->create([
            'quantity' => 0,
            'late_fee_per_day' => 12.00,
        ]);
        $rental = Rental::factory()->approved()->create([
            'gadget_id' => $gadget->id,
            'deposit_amount' => 180.00,
            'end_date' => '2026-07-12',
        ]);

        $this->travelTo(now()->setDate(2026, 7, 12)->setTime(15, 30));

        $response = $this->actingAs($admin)->patch(route('admin.rentals.return', $rental), [
            'condition_on_return' => 'missing_parts',
            'deposit_decision' => 'deduct_all',
            'deposit_deduction_reason' => 'Returned without charger and case.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'status' => 'completed',
            'deposit_status' => 'deducted',
            'deposit_refund_amount' => 0.00,
            'deposit_deduction_reason' => 'Returned without charger and case.',
            'late_fee_amount' => 0.00,
        ]);
    }

    public function test_customer_can_only_review_completed_rentals_and_cannot_submit_duplicate_review(): void
    {
        $customer = User::factory()->customer()->create();
        $notCompletedRental = Rental::factory()->approved()->create([
            'user_id' => $customer->id,
        ]);
        $completedRental = Rental::factory()->completed()->create([
            'user_id' => $customer->id,
        ]);

        $this->actingAs($customer)->post(route('customer.rentals.review.store', $notCompletedRental), [
            'rating' => 4,
            'comment' => 'Tried to review too early.',
        ])->assertRedirect(route('customer.rentals.index'));

        $this->assertDatabaseCount('reviews', 0);

        $this->actingAs($customer)->post(route('customer.rentals.review.store', $completedRental), [
            'rating' => 5,
            'comment' => 'Everything worked perfectly.',
        ])->assertRedirect(route('customer.rentals.index'));

        $this->assertDatabaseHas('reviews', [
            'rental_id' => $completedRental->id,
            'user_id' => $customer->id,
            'gadget_id' => $completedRental->gadget_id,
            'rating' => 5,
        ]);

        $this->actingAs($customer)->post(route('customer.rentals.review.store', $completedRental), [
            'rating' => 3,
            'comment' => 'Duplicate review attempt.',
        ])->assertRedirect(route('customer.rentals.index'));

        $this->assertSame(1, Review::query()->where('rental_id', $completedRental->id)->count());
    }

    public function test_blocked_user_cannot_create_new_rental_request(): void
    {
        $blockedCustomer = User::factory()->customer()->blocked()->create();
        $gadget = Gadget::factory()->create([
            'quantity' => 2,
            'status' => 'active',
        ]);

        $response = $this->actingAs($blockedCustomer)->post(route('customer.rentals.store'), [
            'gadget_id' => $gadget->id,
            'rental_type' => 'hour',
            'rental_hours' => 2,
            'pickup_type' => 'walk_in',
            'agreement_accepted' => true,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('rentals', 0);
    }

    public function test_customer_cannot_access_admin_only_rental_actions(): void
    {
        $customer = User::factory()->customer()->create();
        $pendingRental = Rental::factory()->create([
            'status' => 'pending',
        ]);
        $approvedRental = Rental::factory()->approved()->create([
            'gadget_id' => Gadget::factory()->create([
                'quantity' => 0,
            ])->id,
        ]);

        $this->actingAs($customer)->patch(route('admin.rentals.approve', $pendingRental))
            ->assertForbidden();

        $this->actingAs($customer)->patch(route('admin.rentals.reject', $pendingRental))
            ->assertForbidden();

        $this->actingAs($customer)->patch(route('admin.rentals.return', $approvedRental), [
            'condition_on_return' => 'good',
            'deposit_decision' => 'full_refund',
        ])->assertForbidden();
    }
}
