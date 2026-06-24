<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $gadget = Gadget::query()->findOrFail($validated['gadget_id']);

        abort_unless($gadget->status === 'active' && $gadget->quantity > 0, 404);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $rentalDays = $startDate->diffInDays($endDate) + 1;
        $totalAmount = $rentalDays * $gadget->daily_rental_price;

        Rental::create([
            'user_id' => auth()->id(),
            'gadget_id' => $gadget->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('customer.rentals.index')
            ->with('success', 'Rental request submitted successfully.');
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
            $rental->update(['status' => 'approved']);
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
}
