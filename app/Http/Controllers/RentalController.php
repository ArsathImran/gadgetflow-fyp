<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RentalController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);

        $rentals = Rental::query()
            ->with('gadget')
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

        $validated = $request->validate([
            'gadget_id' => ['required', 'integer', 'exists:gadgets,id'],
            'rental_type' => ['required', 'in:hour,day'],
            'rental_hours' => ['nullable', 'integer', 'min:1', 'required_if:rental_type,hour'],
            'start_date' => ['nullable', 'date', 'required_if:rental_type,day'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date', 'required_if:rental_type,day'],
            'pickup_type' => ['required', 'in:walk_in,delivery'],
            'delivery_address' => ['nullable', 'string', 'required_if:pickup_type,delivery'],
            'phone_number' => ['nullable', 'string', 'max:30', 'required_if:pickup_type,delivery'],
            'ic_number' => ['nullable', 'string', 'max:50', 'required_if:pickup_type,delivery'],
            'agreement_accepted' => ['accepted'],
        ]);

        $gadget = Gadget::query()->findOrFail($validated['gadget_id']);
        abort_unless($gadget->status === 'active' && $gadget->quantity > 0, 404);

        $totalAmount = $this->calculateTotalAmount($gadget, $validated);

        $rentalDates = $this->resolveRentalDates($validated);

        Rental::create([
            'user_id' => auth()->id(),
            'gadget_id' => $gadget->id,
            'rental_type' => $validated['rental_type'],
            'rental_hours' => $validated['rental_type'] === 'hour' ? (int) $validated['rental_hours'] : null,
            'pickup_type' => $validated['pickup_type'],
            'delivery_address' => $validated['pickup_type'] === 'delivery' ? $validated['delivery_address'] : null,
            'phone_number' => $validated['pickup_type'] === 'delivery' ? $validated['phone_number'] : null,
            'ic_number' => $validated['pickup_type'] === 'delivery' ? $validated['ic_number'] : null,
            'agreement_accepted' => true,
            'payment_proof' => null,
            'payment_proofs' => null,
            'payment_note' => null,
            'payment_status' => 'not_required',
            'shipping_status' => 'not_applicable',
            'start_date' => $rentalDates['start_date'],
            'end_date' => $rentalDates['end_date'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('customer.rentals.index')
            ->with('success', 'Rental request submitted successfully.');
    }

    public function paymentCreate(Rental $rental)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($rental->user_id === auth()->id(), 403);
        abort_unless($rental->status === 'approved', 403);
        abort_unless($rental->pickup_type === 'delivery', 403);
        abort_unless($rental->payment_status === 'pending', 403);

        $rental->load('gadget');

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

    public function adminIndex()
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $rentals = Rental::query()
            ->with(['user', 'gadget'])
            ->latest()
            ->paginate(10);

        return view('admin.rentals.index', compact('rentals'));
    }

    public function approve(Rental $rental)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if ($rental->status !== 'pending') {
            return back()->with('error', 'Only pending rental requests can be approved.');
        }

        DB::transaction(function () use ($rental) {
            $gadget = Gadget::query()
                ->whereKey($rental->gadget_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($gadget->quantity < 1) {
                abort(422, 'Cannot approve this request because the gadget is out of stock.');
            }

            $gadget->decrement('quantity');

            $rental->update([
                'status' => 'approved',
                'payment_status' => $rental->pickup_type === 'delivery' ? 'pending' : 'not_required',
                'shipping_status' => $rental->pickup_type === 'delivery' ? 'not_applicable' : 'not_applicable',
            ]);
        });

        return back()->with('success', 'Rental request approved.');
    }

    public function reject(Rental $rental)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if ($rental->status !== 'pending') {
            return back()->with('error', 'Only pending rental requests can be rejected.');
        }

        $rental->update(['status' => 'rejected']);

        return back()->with('success', 'Rental request rejected.');
    }

    public function verifyPayment(Rental $rental)
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

        $rental->update([
            'payment_status' => 'verified',
            'shipping_status' => 'waiting_for_shipping',
        ]);

        return back()->with('success', 'Payment verified successfully.');
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

        return back()->with('success', 'Payment rejected successfully.');
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
}
