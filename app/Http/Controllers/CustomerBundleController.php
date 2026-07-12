<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerBundleController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);

        $validated = $request->validate([
            'type' => ['required', 'in:wedding,short_film'],
        ]);

        $type = $validated['type'];

        $bundles = Bundle::query()
            ->where('status', 'active')
            ->where('type', $type)
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('customer.bundles.index', compact('bundles', 'type'));
    }

    public function show(Bundle $bundle): View
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->isCustomer(), 403);
        abort_unless($bundle->status === 'active', 404);

        return view('customer.bundles.show', compact('bundle'));
    }
}
