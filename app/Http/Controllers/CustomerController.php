<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('rentals')
            ->withSum('rentals', 'total_amount')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%' . $request->string('search') . '%';

                $query->where(function ($customerQuery) use ($search) {
                    $customerQuery
                        ->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        abort_unless($user->isCustomer(), 404);

        $rentals = $user->rentals()
            ->with('gadget')
            ->latest()
            ->paginate(10);

        return view('admin.customers.show', compact('user', 'rentals'));
    }

    public function toggleBlock(User $user)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        abort_unless($user->isCustomer(), 404);

        $user->update([
            'is_blocked' => ! $user->is_blocked,
        ]);

        return back()->with('success', $user->is_blocked ? 'Customer blocked.' : 'Customer unblocked.');
    }
}
