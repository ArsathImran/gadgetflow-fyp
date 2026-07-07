<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Rental $rental)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($rental->user_id === auth()->id(), 403);

        $rental->loadMissing('review');

        if ($rental->status !== 'completed') {
            return redirect()
                ->route('customer.rentals.index')
                ->with('error', 'You can only review completed rentals.');
        }

        if ($rental->review) {
            return redirect()
                ->route('customer.rentals.index')
                ->with('error', 'You have already submitted a review for this rental.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'rental_id' => $rental->id,
            'user_id' => auth()->id(),
            'gadget_id' => $rental->gadget_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()
            ->route('customer.rentals.index')
            ->with('success', 'Thank you for sharing your review.');
    }
}
