<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Gadget;
use App\Models\LoyaltyTransaction;
use App\Models\Rental;
use App\Models\User;
use App\Notifications\GadgetDelivered;
use App\Notifications\GadgetShipped;
use App\Notifications\HandoverConfirmed;
use App\Notifications\OutForDelivery;
use App\Notifications\PaymentRejected;
use App\Notifications\PaymentVerified;
use App\Notifications\RentalApproved;
use App\Notifications\RentalCompleted;
use App\Notifications\RentalRejected;
use App\Notifications\RentalSubmitted;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RentalController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);

        $rentals = Rental::query()
            ->with(['gadget.category', 'bundle', 'collectedByAdmin', 'review', 'loyaltyTransactions'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.rentals.index', compact('rentals'));
    }

    public function create(Gadget $gadget)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($gadget->status === 'active' && $gadget->quantity > 0, 404);

        return view('customer.rentals.create', compact('gadget'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_if(auth()->user()->is_blocked, 403, 'Your account has been blocked from making rental requests. Please contact support.');

        $validated = $request->validate([
            'gadget_id' => ['nullable', 'integer', 'exists:gadgets,id', 'required_without:bundle_id'],
            'bundle_id' => ['nullable', 'integer', 'exists:bundles,id', 'required_without:gadget_id'],
            'rental_type' => ['required', 'in:hour,day'],
            'rental_hours' => ['nullable', 'integer', 'min:1', 'required_if:rental_type,hour'],
            'start_date' => ['nullable', 'date', 'required_if:rental_type,day'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date', 'required_if:rental_type,day'],
            'pickup_type' => ['nullable', 'in:walk_in,delivery', 'required_without:bundle_id'],
            'delivery_address' => ['nullable', 'string', 'required_if:pickup_type,delivery'],
            'phone_number' => ['nullable', 'string', 'max:30', 'required_if:pickup_type,delivery'],
            'id_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'],
            'agreement_accepted' => ['accepted'],
            'redeem_points' => ['nullable', 'integer', 'min:0'],
        ]);

        if (! empty($validated['gadget_id']) && ! empty($validated['bundle_id'])) {
            abort(422, 'A rental request can only be created for one item at a time.');
        }

        $gadget = null;
        $bundle = null;
        $pickupType = $validated['pickup_type'] ?? 'walk_in';

        if (! empty($validated['bundle_id'])) {
            $bundle = Bundle::query()->findOrFail($validated['bundle_id']);
            abort_unless($bundle->status === 'active', 404);
            $pickupType = 'walk_in';
        } else {
            $gadget = Gadget::query()->findOrFail($validated['gadget_id']);
            abort_unless($gadget->status === 'active' && $gadget->quantity > 0, 404);
            abort_unless(in_array($pickupType, ['walk_in', 'delivery'], true), 422);
        }

        if ($pickupType === 'delivery' && ! auth()->user()->id_document_path && ! $request->hasFile('id_document')) {
            throw ValidationException::withMessages([
                'id_document' => 'Please upload a supporting ID document to continue with a delivery order.',
            ]);
        }

        if ($request->hasFile('id_document')) {
            $user = auth()->user();

            if ($user->id_document_path) {
                Storage::disk('public')->delete($user->id_document_path);
            }

            $user->id_document_path = $request->file('id_document')->store('id-documents', 'public');
            $user->id_document_uploaded_at = now();
            $user->save();
        }

        $totalAmount = $bundle
            ? $this->calculateBundleTotalAmount($bundle, $validated)
            : $this->calculateTotalAmount($gadget, $validated);

        $rentalDates = $this->resolveRentalDates($validated);
        $qrToken = $this->generateUniqueQrToken();
        $requestedRedeemPoints = (int) ($validated['redeem_points'] ?? 0);

        $rental = null;

        DB::transaction(function () use (&$rental, $gadget, $bundle, $qrToken, $validated, $pickupType, $rentalDates, $totalAmount, $requestedRedeemPoints) {
            $pointsRedeemed = 0;
            $discountAmount = 0;
            $finalTotalAmount = $totalAmount;

            if ($requestedRedeemPoints > 0) {
                $user = User::query()->whereKey(auth()->id())->lockForUpdate()->firstOrFail();
                $redemptionRate = (float) config('loyalty.redemption_rate');
                $maxPointsByAmount = $redemptionRate > 0 ? (int) floor($totalAmount / $redemptionRate) : 0;
                $pointsRedeemed = min($requestedRedeemPoints, $user->loyalty_points, $maxPointsByAmount);

                if ($pointsRedeemed > 0) {
                    $discountAmount = round($pointsRedeemed * $redemptionRate, 2);
                    $finalTotalAmount = round($totalAmount - $discountAmount, 2);
                    $user->decrement('loyalty_points', $pointsRedeemed);
                }
            }

            $rental = Rental::create([
                'user_id' => auth()->id(),
                'gadget_id' => $gadget?->id,
                'bundle_id' => $bundle?->id,
                'qr_token' => $qrToken,
                'rental_type' => $validated['rental_type'],
                'rental_hours' => $validated['rental_type'] === 'hour' ? (int) $validated['rental_hours'] : null,
                'pickup_type' => $pickupType,
                'delivery_address' => $pickupType === 'delivery' ? $validated['delivery_address'] : null,
                'phone_number' => $pickupType === 'delivery' ? $validated['phone_number'] : null,
                'agreement_accepted' => true,
                'payment_proof' => null,
                'payment_proofs' => null,
                'payment_note' => null,
                'payment_status' => 'not_required',
                'shipping_status' => 'not_applicable',
                'start_date' => $rentalDates['start_date'],
                'end_date' => $rentalDates['end_date'],
                'total_amount' => $finalTotalAmount,
                'points_redeemed' => $pointsRedeemed,
                'discount_amount' => $discountAmount,
                'deposit_amount' => $bundle?->deposit_amount ?? $gadget?->deposit_amount ?? 0,
                'status' => 'pending',
            ]);

            if ($pointsRedeemed > 0) {
                LoyaltyTransaction::create([
                    'user_id' => auth()->id(),
                    'rental_id' => $rental->id,
                    'type' => 'redeemed',
                    'points' => -$pointsRedeemed,
                    'description' => "Redeemed for rental #{$rental->id}",
                ]);
            }
        });

        $rental->refresh()->loadMissing(['user', 'gadget', 'bundle']);
        $rental->user->notify(new RentalSubmitted($rental));

        return redirect()
            ->route('customer.rentals.index')
            ->with('success', 'Rental request submitted successfully.');
    }

    public function showQr(Rental $rental)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($rental->user_id === auth()->id(), 403);
        abort_unless($rental->status === 'approved', 403);
        abort_unless(! empty($rental->qr_token), 403);

        $rental->load(['gadget', 'bundle']);

        $scanUrl = route('admin.scan', ['token' => $rental->qr_token]);
        $qrSvg = QrCode::size(300)->generate($scanUrl);

        return view('customer.rentals.qr', compact('rental', 'qrSvg'));
    }

    public function paymentCreate(Rental $rental)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($rental->user_id === auth()->id(), 403);
        abort_unless($rental->status === 'approved', 403);
        abort_unless($rental->pickup_type === 'delivery', 403);
        abort_unless($rental->payment_status === 'pending', 403);

        $rental->load(['gadget', 'bundle']);

        return view('customer.rentals.payment', compact('rental'));
    }

    public function paymentStore(Request $request, Rental $rental)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($rental->user_id === auth()->id(), 403);
        abort_unless($rental->status === 'approved', 403);
        abort_unless($rental->pickup_type === 'delivery', 403);
        abort_unless($rental->payment_status === 'pending', 403);

        $validated = $request->validate([
            'payment_proofs' => ['required', 'array', 'min:1'],
            'payment_proofs.*' => ['required', 'file', 'max:5120'],
            'payment_note' => ['nullable', 'string'],
        ]);

        $existingProofs = collect($rental->payment_proofs ?? []);

        if ($existingProofs->isNotEmpty()) {
            Storage::disk('public')->delete($existingProofs->all());
        } elseif ($rental->payment_proof) {
            Storage::disk('public')->delete($rental->payment_proof);
        }

        $paths = collect($request->file('payment_proofs'))
            ->map(fn ($file) => $file->store('payments', 'public'))
            ->values()
            ->all();

        $rental->update([
            'payment_proof' => $paths[0] ?? null,
            'payment_proofs' => $paths,
            'payment_note' => $validated['payment_note'] ?? null,
            'payment_status' => 'pending',
        ]);

        return redirect()
            ->route('customer.rentals.index')
            ->with('success', 'Payment proof uploaded successfully.');
    }

    public function cancel(Rental $rental)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($rental->user_id === auth()->id(), 403);

        if ($rental->status !== 'pending') {
            return redirect()
                ->route('customer.rentals.index')
                ->with('error', 'Only pending rental requests can be cancelled.');
        }

        DB::transaction(function () use ($rental) {
            $rental->update([
                'status' => 'cancelled_by_customer',
            ]);

            if ($rental->points_redeemed > 0) {
                $user = User::query()->whereKey($rental->user_id)->lockForUpdate()->firstOrFail();
                $user->increment('loyalty_points', $rental->points_redeemed);

                LoyaltyTransaction::create([
                    'user_id' => $rental->user_id,
                    'rental_id' => $rental->id,
                    'type' => 'refunded',
                    'points' => $rental->points_redeemed,
                    'description' => "Refunded - rental request #{$rental->id} cancelled by customer",
                ]);
            }
        });

        return redirect()
            ->route('customer.rentals.index')
            ->with('success', 'Your rental request has been cancelled.');
    }

    public function adminIndex()
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $rentals = Rental::query()
            ->with(['user', 'gadget.category', 'bundle', 'collectedByAdmin', 'review'])
            ->latest()
            ->paginate(10);

        return view('admin.rentals.index', compact('rentals'));
    }

    public function show(Request $request, Rental $rental)
    {
        abort_unless(auth()->check(), 403);

        if ($request->routeIs('admin.rentals.show')) {
            abort_unless(auth()->user()->isAdmin(), 403);

            $rental->load(['user', 'gadget.category', 'bundle', 'collectedByAdmin', 'review']);

            return view('admin.rentals.show', compact('rental'));
        }

        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($rental->user_id === auth()->id(), 403);

        $rental->load(['gadget.category', 'bundle', 'collectedByAdmin', 'review', 'loyaltyTransactions']);

        return view('customer.rentals.show', compact('rental'));
    }

    public function scan()
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        return view('admin.scan');
    }

    public function lookupByToken(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        $rental = Rental::query()
            ->with(['gadget', 'bundle', 'user'])
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if (! $rental) {
            return response()->json([
                'message' => 'No rental was found for the scanned QR code.',
            ], 404);
        }

        return response()->json([
            'id' => $rental->id,
            'gadget_name' => $rental->itemName(),
            'item_name' => $rental->itemName(),
            'customer_name' => $rental->user?->name,
            'status' => $rental->status,
            'handed_over_at' => $rental->handed_over_at?->toIso8601String(),
            'returned_at' => $rental->returned_at?->toIso8601String(),
            'pickup_type' => $rental->pickup_type,
            'deposit_amount' => (float) ($rental->deposit_amount ?? 0),
            'days_overdue' => $rental->daysOverdue(),
            'late_fee_amount' => $rental->daysOverdue() > 0
                ? $rental->daysOverdue() * (float) ($rental->isBundle()
                    ? ($rental->bundle?->late_fee_per_day ?? 0)
                    : ($rental->gadget?->late_fee_per_day ?? 0))
                : 0,
            'next_action' => $this->determineScanNextAction($rental),
        ]);
    }

    public function confirmHandover(Rental $rental)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if ($rental->status !== 'approved') {
            return response()->json([
                'message' => 'Only approved rentals can be handed over.',
            ], 422);
        }

        if ($rental->handed_over_at !== null) {
            return response()->json([
                'message' => 'This rental has already been marked as handed over.',
            ], 422);
        }

        $isDelivery = $rental->pickup_type === 'delivery';
        $collectsPaymentNow = ! $isDelivery && $rental->payment_status === 'pending_collection';

        $rental->update([
            'handed_over_at' => now(),
            ...($isDelivery ? ['shipping_status' => 'delivered'] : []),
            ...($collectsPaymentNow ? [
                'payment_status' => 'collected',
                'payment_collected_at' => now(),
                'payment_collected_by' => auth()->id(),
            ] : []),
        ]);

        $rental->refresh()->loadMissing(['user', 'gadget', 'bundle']);
        $rental->user->notify($isDelivery ? new GadgetDelivered($rental) : new HandoverConfirmed($rental));

        return response()->json([
            'message' => 'Handover confirmed successfully.',
            'handed_over_at' => $rental->handed_over_at?->toIso8601String(),
        ]);
    }

    public function approve(Rental $rental)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if ($rental->status !== 'pending') {
            return back()->with('error', 'Only pending rental requests can be approved.');
        }

        DB::transaction(function () use ($rental) {
            if (! $rental->isBundle()) {
                $gadget = Gadget::query()
                    ->whereKey($rental->gadget_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($gadget->quantity < 1) {
                    abort(422, 'Cannot approve this request because the gadget is out of stock.');
                }

                $gadget->decrement('quantity');
            }

            $rental->update([
                'status' => 'approved',
                'payment_status' => $rental->pickup_type === 'delivery' ? 'pending' : 'pending_collection',
                // shipping_status stays not_applicable here even for delivery orders; it moves to
                // waiting_for_shipping once verifyPayment() confirms the delivery payment proof.
                'shipping_status' => 'not_applicable',
            ]);
        });

        $rental->refresh()->loadMissing(['user', 'gadget', 'bundle']);
        $rental->user->notify(new RentalApproved($rental));

        return back()->with('success', 'Rental request approved.');
    }

    public function reject(Rental $rental)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if ($rental->status !== 'pending') {
            return back()->with('error', 'Only pending rental requests can be rejected.');
        }

        DB::transaction(function () use ($rental) {
            $rental->update(['status' => 'rejected']);

            if ($rental->points_redeemed > 0) {
                $user = User::query()->whereKey($rental->user_id)->lockForUpdate()->firstOrFail();
                $user->increment('loyalty_points', $rental->points_redeemed);

                LoyaltyTransaction::create([
                    'user_id' => $rental->user_id,
                    'rental_id' => $rental->id,
                    'type' => 'refunded',
                    'points' => $rental->points_redeemed,
                    'description' => "Refunded - rental request #{$rental->id} rejected",
                ]);
            }
        });

        $rental->refresh()->loadMissing(['user', 'gadget', 'bundle']);
        $rental->user->notify(new RentalRejected($rental));

        return back()->with('success', 'Rental request rejected.');
    }

    public function collectPayment(Request $request, Rental $rental)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($rental->pickup_type !== 'walk_in' || $rental->payment_status !== 'pending_collection') {
            abort(422, 'Only walk-in rentals awaiting collection can be marked as paid.');
        }

        $rental->update([
            'payment_status' => 'collected',
            'payment_collected_at' => now(),
            'payment_collected_by' => auth()->id(),
        ]);

        return back()->with('success', 'Walk-in payment marked as collected successfully.');
    }

    public function markReturned(Request $request, Rental $rental)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($rental->status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Only approved rentals can be marked as returned.',
                ], 422);
            }

            abort(422, 'Only approved rentals can be marked as returned.');
        }

        // Refunds are based on what was actually collected (deposit_amount_received), falling back
        // to the theoretical deposit_amount for walk-in/older rentals that never went through the
        // partial-payment verification flow.
        $depositAmount = (float) ($rental->deposit_amount_received ?? $rental->deposit_amount ?? 0);

        $validated = $request->validate([
            'condition_on_return' => ['required', 'in:good,damaged,missing_parts'],
            'return_notes' => ['nullable', 'string'],
            'deposit_decision' => ['required', 'in:full_refund,partial_refund,deduct_all'],
            'deposit_refund_amount' => [
                'nullable',
                'required_if:deposit_decision,partial_refund',
                'numeric',
                'min:0',
                'max:' . $depositAmount,
            ],
            'deposit_deduction_reason' => [
                'nullable',
                'string',
                'required_if:deposit_decision,partial_refund,deduct_all',
            ],
            'waive_late_fee' => ['nullable', 'boolean'],
        ]);

        $depositStatus = 'refunded';
        $depositRefundAmount = $depositAmount;
        $daysOverdue = $rental->daysOverdue();
        $lateFeeRate = (float) ($rental->isBundle()
            ? ($rental->bundle?->late_fee_per_day ?? 0)
            : ($rental->gadget?->late_fee_per_day ?? 0));
        $lateFeeAmount = $daysOverdue > 0
            ? $daysOverdue * $lateFeeRate
            : 0;
        $lateFeeWaived = $request->boolean('waive_late_fee');

        if ($lateFeeWaived) {
            $lateFeeAmount = 0;
        }

        if ($validated['deposit_decision'] === 'partial_refund') {
            $depositStatus = 'partially_refunded';
            $depositRefundAmount = (float) $validated['deposit_refund_amount'];
        }

        if ($validated['deposit_decision'] === 'deduct_all') {
            $depositStatus = 'deducted';
            $depositRefundAmount = 0;
        }

        DB::transaction(function () use ($rental, $validated, $depositStatus, $depositRefundAmount, $lateFeeAmount, $lateFeeWaived) {
            if (! $rental->isBundle()) {
                $gadget = Gadget::query()
                    ->whereKey($rental->gadget_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $gadget->increment('quantity');
            }

            $rental->update([
                'status' => 'completed',
                'returned_at' => now(),
                'condition_on_return' => $validated['condition_on_return'],
                'return_notes' => $validated['return_notes'] ?? null,
                'deposit_status' => $depositStatus,
                'deposit_refund_amount' => $depositRefundAmount,
                'deposit_deduction_reason' => $validated['deposit_deduction_reason'] ?? null,
                'late_fee_amount' => $lateFeeAmount,
                'late_fee_waived' => $lateFeeWaived,
            ]);

            $pointsEarned = (int) floor((float) $rental->total_amount * (float) config('loyalty.points_per_currency_unit'));

            if ($pointsEarned > 0) {
                $user = User::query()->whereKey($rental->user_id)->lockForUpdate()->firstOrFail();
                $user->increment('loyalty_points', $pointsEarned);
                $user->increment('lifetime_points', $pointsEarned);

                LoyaltyTransaction::create([
                    'user_id' => $rental->user_id,
                    'rental_id' => $rental->id,
                    'type' => 'earned',
                    'points' => $pointsEarned,
                    'description' => "Earned from completed rental #{$rental->id}",
                ]);
            }
        });

        $rental->refresh()->loadMissing(['user', 'gadget', 'bundle']);
        $rental->user->notify(new RentalCompleted($rental));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Return confirmed successfully.',
                'rental_id' => $rental->id,
                'returned_at' => $rental->returned_at?->toIso8601String(),
                'deposit_status' => $rental->deposit_status,
                'deposit_refund_amount' => (float) ($rental->deposit_refund_amount ?? 0),
                'late_fee_amount' => (float) ($rental->late_fee_amount ?? 0),
                'late_fee_waived' => (bool) $rental->late_fee_waived,
            ]);
        }

        return back()->with('success', 'Rental marked as returned successfully.');
    }

    public function verifyPayment(Request $request, Rental $rental)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        $paymentProofs = $rental->payment_proofs ?? ($rental->payment_proof ? [$rental->payment_proof] : []);

        if (
            $rental->pickup_type !== 'delivery'
            || $rental->payment_status !== 'pending'
            || count($paymentProofs) === 0
        ) {
            return back()->with('error', 'Only pending payment proofs can be verified.');
        }

        $rentalPaidFull = $request->boolean('rental_paid_full');
        $depositPaidFull = $request->boolean('deposit_paid_full');

        $validated = $request->validate([
            'rental_amount_received' => [
                Rule::requiredIf(! $rentalPaidFull),
                'nullable',
                'numeric',
                'min:0',
                'max:' . (float) $rental->total_amount,
            ],
            'deposit_amount_received' => [
                Rule::requiredIf(! $depositPaidFull),
                'nullable',
                'numeric',
                'min:0',
                'max:' . (float) ($rental->deposit_amount ?? 0),
            ],
            'shortfall_notes' => ['nullable', 'string'],
        ]);

        $rentalAmountReceived = $rentalPaidFull
            ? (float) $rental->total_amount
            : (float) $validated['rental_amount_received'];

        $depositAmountReceived = $depositPaidFull
            ? (float) ($rental->deposit_amount ?? 0)
            : (float) $validated['deposit_amount_received'];

        $paymentStatus = ($rentalPaidFull && $depositPaidFull) ? 'verified' : 'partially_verified';

        $rental->update([
            'payment_status' => $paymentStatus,
            'shipping_status' => 'waiting_for_shipping',
            'rental_amount_received' => $rentalAmountReceived,
            'deposit_amount_received' => $depositAmountReceived,
            'payment_shortfall_notes' => $validated['shortfall_notes'] ?? null,
        ]);

        $rental->refresh()->loadMissing(['user', 'gadget', 'bundle']);
        $rental->user->notify(new PaymentVerified($rental));

        return back()->with('success', $paymentStatus === 'verified'
            ? 'Payment verified successfully.'
            : 'Payment partially verified — shortfall recorded.');
    }

    public function rejectPayment(Rental $rental)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        $paymentProofs = $rental->payment_proofs ?? ($rental->payment_proof ? [$rental->payment_proof] : []);

        if (
            $rental->pickup_type !== 'delivery'
            || $rental->payment_status !== 'pending'
            || count($paymentProofs) === 0
        ) {
            return back()->with('error', 'Only pending payment proofs can be rejected.');
        }

        $rental->update([
            'payment_status' => 'rejected',
        ]);

        $rental->refresh()->loadMissing(['user', 'gadget', 'bundle']);
        $rental->user->notify(new PaymentRejected($rental));

        return back()->with('success', 'Payment rejected successfully.');
    }

    public function updateShipping(Request $request, Rental $rental)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        abort_unless($rental->pickup_type === 'delivery', 422, 'Only delivery orders have a shipping status to update.');

        $validated = $request->validate([
            'shipping_status' => ['required', 'in:waiting_for_shipping,shipped,out_for_delivery,delivered'],
        ]);

        $stages = ['waiting_for_shipping', 'shipped', 'out_for_delivery', 'delivered'];
        $currentIndex = array_search($rental->shipping_status, $stages, true);
        $requestedIndex = array_search($validated['shipping_status'], $stages, true);

        if ($currentIndex !== false && $requestedIndex < $currentIndex) {
            return back()->with('error', 'Shipping status cannot be moved backwards.');
        }

        $rental->update([
            'shipping_status' => $validated['shipping_status'],
        ]);

        $rental->refresh()->loadMissing(['user', 'gadget', 'bundle']);

        match ($validated['shipping_status']) {
            'shipped' => $rental->user->notify(new GadgetShipped($rental)),
            'out_for_delivery' => $rental->user->notify(new OutForDelivery($rental)),
            'delivered' => $rental->user->notify(new GadgetDelivered($rental)),
            default => null,
        };

        return back()->with('success', 'Shipping status updated successfully.');
    }

    private function calculateTotalAmount(Gadget $gadget, array $validated): float
    {
        if ($validated['rental_type'] === 'hour') {
            $hourlyPrice = $gadget->hourly_rental_price ?? $gadget->daily_rental_price;

            return (float) $validated['rental_hours'] * (float) $hourlyPrice;
        }

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $rentalDays = $startDate->diffInDays($endDate) + 1;

        return (float) $rentalDays * (float) $gadget->daily_rental_price;
    }

    private function calculateBundleTotalAmount(Bundle $bundle, array $validated): float
    {
        if ($validated['rental_type'] === 'hour') {
            $hourlyPrice = $bundle->hourly_rental_price ?? $bundle->daily_rental_price ?? 0;

            return (float) $validated['rental_hours'] * (float) $hourlyPrice;
        }

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $rentalDays = $startDate->diffInDays($endDate) + 1;

        return (float) $rentalDays * (float) ($bundle->daily_rental_price ?? 0);
    }

    private function resolveRentalDates(array $validated): array
    {
        if ($validated['rental_type'] === 'hour') {
            $today = now()->toDateString();

            return [
                'start_date' => $today,
                'end_date' => $today,
            ];
        }

        return [
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ];
    }

    private function generateUniqueQrToken(): string
    {
        do {
            $token = Str::random(40);
        } while (Rental::query()->where('qr_token', $token)->exists());

        return $token;
    }

    private function determineScanNextAction(Rental $rental): string
    {
        if ($rental->returned_at !== null) {
            return 'already_completed';
        }

        if ($rental->handed_over_at !== null) {
            return 'return';
        }

        if ($rental->status === 'approved') {
            return 'handover';
        }

        return 'not_ready';
    }
}
