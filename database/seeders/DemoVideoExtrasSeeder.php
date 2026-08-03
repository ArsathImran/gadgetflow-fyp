<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\Gadget;
use App\Models\Inquiry;
use App\Models\LoyaltyTransaction;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Purely additive extra demo data for recording a walkthrough video.
 * Safe to re-run: php artisan db:seed --class=DemoVideoExtrasSeeder
 *
 * Does NOT touch the admin user, does NOT wipe/reset anything, and is
 * intentionally excluded from DatabaseSeeder's default --seed chain.
 */
class DemoVideoExtrasSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $stockUpdated = $this->bumpGadgetStock();

        $demoCustomer = $this->seedPendingApprovalRequest($now);
        $returnScanRental = $this->seedReturnScanReadyRental($now);
        $outForDeliveryRental = $this->seedOutForDeliveryRental($now);

        $this->seedLoyaltyLedger($returnScanRental);
        $this->seedInquiries();
        $chatCustomer = $this->seedChatMessages();

        $this->printSummary($stockUpdated, $demoCustomer, $returnScanRental, $outForDeliveryRental, $chatCustomer);
    }

    private function bumpGadgetStock(): int
    {
        return Gadget::query()->update(['quantity' => 10]);
    }

    private function generateUniqueQrToken(): string
    {
        do {
            $token = Str::random(40);
        } while (Rental::query()->where('qr_token', $token)->exists());

        return $token;
    }

    /**
     * New customer + one 'pending' rental request, left un-approved on purpose
     * so it can be approved live on camera.
     */
    private function seedPendingApprovalRequest(Carbon $now): User
    {
        $customer = User::query()->firstOrCreate(
            ['email' => 'priya@gadgetflow.test'],
            [
                'name' => 'Priya Menon',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_blocked' => false,
                'email_verified_at' => $now,
            ]
        );

        $gadget = Gadget::query()->where('name', 'Google Pixel 9 Pro')->firstOrFail();

        $alreadyExists = Rental::query()
            ->where('user_id', $customer->id)
            ->where('gadget_id', $gadget->id)
            ->where('status', 'pending')
            ->exists();

        if (! $alreadyExists) {
            Rental::query()->create([
                'user_id' => $customer->id,
                'gadget_id' => $gadget->id,
                'qr_token' => $this->generateUniqueQrToken(),
                'rental_type' => 'day',
                'pickup_type' => 'walk_in',
                'agreement_accepted' => true,
                'payment_status' => 'not_required',
                'shipping_status' => 'not_applicable',
                'start_date' => $now->copy()->addDays(2)->toDateString(),
                'end_date' => $now->copy()->addDays(4)->toDateString(),
                'total_amount' => 184.00,
                'deposit_amount' => $gadget->deposit_amount,
                'status' => 'pending',
            ]);
        }

        return $customer;
    }

    /**
     * Approved rental, already handed over, not yet returned - ready to be
     * scanned live for the QR return-scan demo without a handover scan first.
     * Also carries a real points-redeemed discount so the loyalty ledger
     * entry created in seedLoyaltyLedger() lines up with an actual rental.
     */
    private function seedReturnScanReadyRental(Carbon $now): Rental
    {
        $customer = User::query()->where('email', 'daniel@gadgetflow.test')->firstOrFail();
        $gadget = Gadget::query()->where('name', 'Nintendo Switch OLED')->firstOrFail();

        $existing = Rental::query()
            ->where('user_id', $customer->id)
            ->where('gadget_id', $gadget->id)
            ->where('status', 'approved')
            ->whereNotNull('handed_over_at')
            ->whereNull('returned_at')
            ->first();

        if ($existing) {
            return $existing;
        }

        $admin = User::query()->where('role', 'admin')->firstOrFail();
        $handedOverAt = $now->copy()->subHours(2);

        return Rental::query()->create([
            'user_id' => $customer->id,
            'gadget_id' => $gadget->id,
            'qr_token' => $this->generateUniqueQrToken(),
            'rental_type' => 'day',
            'pickup_type' => 'walk_in',
            'agreement_accepted' => true,
            'payment_status' => 'collected',
            'shipping_status' => 'not_applicable',
            'start_date' => $now->copy()->subDay()->toDateString(),
            'end_date' => $now->copy()->addDay()->toDateString(),
            'total_amount' => 120.00, // 2 days x RM70 - RM20 loyalty discount
            'points_redeemed' => 200,
            'discount_amount' => 20.00,
            'deposit_amount' => $gadget->deposit_amount,
            'status' => 'approved',
            'payment_collected_at' => $handedOverAt,
            'payment_collected_by' => $admin->id,
            'handed_over_at' => $handedOverAt,
        ]);
    }

    /**
     * Approved delivery rental sitting specifically at shipping_status = out_for_delivery.
     */
    private function seedOutForDeliveryRental(Carbon $now): Rental
    {
        $customer = User::query()->where('email', 'nadia@gadgetflow.test')->firstOrFail();
        $gadget = Gadget::query()->where('name', 'Samsung Galaxy S25')->firstOrFail();

        $existing = Rental::query()
            ->where('user_id', $customer->id)
            ->where('gadget_id', $gadget->id)
            ->where('shipping_status', 'out_for_delivery')
            ->first();

        if ($existing) {
            return $existing;
        }

        return Rental::query()->create([
            'user_id' => $customer->id,
            'gadget_id' => $gadget->id,
            'qr_token' => $this->generateUniqueQrToken(),
            'rental_type' => 'day',
            'pickup_type' => 'delivery',
            'delivery_address' => '8 Lorong Damai, Johor Bahru',
            'phone_number' => '0134442211',
            'ic_number' => '950505-01-7812',
            'agreement_accepted' => true,
            'payment_proof' => 'payments/demo-proof-8.jpg',
            'payment_proofs' => ['payments/demo-proof-8.jpg'],
            'payment_status' => 'verified',
            'shipping_status' => 'out_for_delivery',
            'start_date' => $now->copy()->subDay()->toDateString(),
            'end_date' => $now->copy()->addDay()->toDateString(),
            'total_amount' => 190.00,
            'deposit_amount' => $gadget->deposit_amount,
            'status' => 'approved',
        ]);
    }

    /**
     * Loyalty ledger covering Bronze / Silver / Gold tiers across 3 existing
     * seeded customers, with User.loyalty_points/lifetime_points kept in
     * lockstep with the ledger (lifetime = sum of 'earned' rows, balance =
     * sum of all rows), mirroring RentalController's own bookkeeping.
     */
    private function seedLoyaltyLedger(Rental $returnScanRental): void
    {
        // Bronze: lifetime stays under the 500-point silver threshold.
        $this->seedLoyaltyForCustomer('farah@gadgetflow.test', 'Earned from completed rental #13', [
            ['rental_id' => 13, 'type' => 'earned', 'points' => 40, 'description' => 'Earned from completed rental #13'],
            ['rental_id' => 18, 'type' => 'earned', 'points' => 126, 'description' => 'Earned from completed rental #18'],
        ]);

        // Silver: lifetime lands between the 500 and 2000 thresholds, includes a redemption
        // tied to the return-scan-ready rental so Rental.points_redeemed matches the ledger.
        $this->seedLoyaltyForCustomer('daniel@gadgetflow.test', 'Earned from completed rental #12', [
            ['rental_id' => 12, 'type' => 'earned', 'points' => 585, 'description' => 'Earned from completed rental #12'],
            ['rental_id' => 17, 'type' => 'earned', 'points' => 540, 'description' => 'Earned from completed rental #17'],
            ['rental_id' => $returnScanRental->id, 'type' => 'redeemed', 'points' => -200, 'description' => "Redeemed for rental #{$returnScanRental->id}"],
        ]);

        // Gold: lifetime clears the 2000-point threshold via a legacy/launch bonus on top
        // of real completed-rental earnings, plus a redemption.
        $this->seedLoyaltyForCustomer('jason@gadgetflow.test', 'Earned - loyalty program launch bonus', [
            ['rental_id' => 14, 'type' => 'earned', 'points' => 210, 'description' => 'Earned from completed rental #14'],
            ['rental_id' => null, 'type' => 'earned', 'points' => 2200, 'description' => 'Earned - loyalty program launch bonus'],
            ['rental_id' => null, 'type' => 'redeemed', 'points' => -300, 'description' => 'Redeemed for RM30.00 rental discount'],
        ]);
    }

    private function seedLoyaltyForCustomer(string $email, string $marker, array $entries): void
    {
        $user = User::query()->where('email', $email)->firstOrFail();

        $alreadySeeded = LoyaltyTransaction::query()
            ->where('user_id', $user->id)
            ->where('description', $marker)
            ->exists();

        if ($alreadySeeded) {
            return;
        }

        foreach ($entries as $entry) {
            LoyaltyTransaction::query()->create([
                'user_id' => $user->id,
                'rental_id' => $entry['rental_id'],
                'type' => $entry['type'],
                'points' => $entry['points'],
                'description' => $entry['description'],
            ]);
        }

        $lifetimeGain = collect($entries)->where('type', 'earned')->sum('points');
        $balanceGain = collect($entries)->sum('points');

        $user->increment('lifetime_points', $lifetimeGain);
        $user->increment('loyalty_points', $balanceGain);
    }

    /**
     * One 'open' inquiry (no reply yet) and one 'responded' inquiry (with a
     * realistic admin_reply), on customers that don't already have any.
     */
    private function seedInquiries(): void
    {
        $nadia = User::query()->where('email', 'nadia@gadgetflow.test')->firstOrFail();
        $farah = User::query()->where('email', 'farah@gadgetflow.test')->firstOrFail();

        Inquiry::query()->firstOrCreate(
            ['user_id' => $nadia->id, 'subject' => 'Delivery time for weekend rental'],
            [
                'name' => $nadia->name,
                'email' => $nadia->email,
                'message' => "Hi, I have a Samsung Galaxy S25 rental arriving by delivery this weekend. What time window should I expect the courier, and do I need to be home to sign for it?",
                'status' => 'open',
                'admin_reply' => null,
                'replied_at' => null,
            ]
        );

        Inquiry::query()->firstOrCreate(
            ['user_id' => $farah->id, 'subject' => 'Can I extend my current rental?'],
            [
                'name' => $farah->name,
                'email' => $farah->email,
                'message' => "I'm currently renting the Bose QuietComfort Ultra and would like to extend it by a few more days. Is that possible, and how do I request it?",
                'status' => 'responded',
                'admin_reply' => "Hi Farah, yes we can extend your rental! Just let us know the new return date and we'll update your booking and send a revised total. Thanks for renting with GadgetFlow.",
                'replied_at' => now()->subDay(),
            ]
        );
    }

    /**
     * Realistic AI chat history for one customer.
     */
    private function seedChatMessages(): User
    {
        $jason = User::query()->where('email', 'jason@gadgetflow.test')->firstOrFail();

        $alreadySeeded = ChatMessage::query()->where('user_id', $jason->id)->exists();

        if (! $alreadySeeded) {
            $conversation = [
                ['role' => 'user', 'message' => 'Hi, do you have any cameras available for a weekend shoot?'],
                ['role' => 'assistant', 'message' => "Hi Jason! Yes, we currently have the Canon EOS R6 Mark II and Sony ZV-E1 available. Both are great for weekend shoots. Would you like details on pricing or availability for specific dates?"],
                ['role' => 'user', 'message' => 'whats the deposit for the canon one'],
                ['role' => 'assistant', 'message' => "The Canon EOS R6 Mark II has a refundable deposit of RM700.00, plus a daily rental rate of RM210.00. The deposit is refunded after the gadget is returned in good condition."],
                ['role' => 'user', 'message' => 'can i pick it up same day'],
                ['role' => 'assistant', 'message' => "Same-day walk-in pickup is available if the gadget is in stock for your selected date! Just submit a rental request and our team will approve it as quickly as possible."],
            ];

            $base = now()->subDays(3)->setTime(14, 0);

            foreach ($conversation as $index => $entry) {
                ChatMessage::query()->create([
                    'user_id' => $jason->id,
                    'role' => $entry['role'],
                    'message' => $entry['message'],
                    'created_at' => $base->copy()->addMinutes($index * 2),
                    'updated_at' => $base->copy()->addMinutes($index * 2),
                ]);
            }
        }

        return $jason;
    }

    private function printSummary(
        int $stockUpdated,
        User $demoCustomer,
        Rental $returnScanRental,
        Rental $outForDeliveryRental,
        User $chatCustomer,
    ): void {
        if (! $this->command) {
            return;
        }

        $pendingRental = Rental::query()
            ->where('user_id', $demoCustomer->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $this->command->info('--- DemoVideoExtrasSeeder summary ---');
        $this->command->info("Gadget stock: {$stockUpdated} gadget(s) set to quantity 10.");
        $this->command->info("Pending-approval demo: rental #{$pendingRental?->id} for {$demoCustomer->email} (password: password) - status is 'pending', approve it live.");
        $this->command->info("Return-scan demo: rental #{$returnScanRental->id} (Daniel Lee / daniel@gadgetflow.test) - approved, handed over, not yet returned.");
        $this->command->info("Out-for-delivery demo: rental #{$outForDeliveryRental->id} (Nadia Ismail / nadia@gadgetflow.test) - shipping_status = out_for_delivery.");
        $this->command->info("Chat history seeded for: {$chatCustomer->email}.");
        $this->command->info('Loyalty tiers seeded: farah@gadgetflow.test (Bronze), daniel@gadgetflow.test (Silver), jason@gadgetflow.test (Gold).');
        $this->command->info('All demo customer passwords: password');
    }
}
