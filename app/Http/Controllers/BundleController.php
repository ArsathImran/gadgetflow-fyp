<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BundleController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $bundles = Bundle::query()
            ->latest()
            ->paginate(10);

        return view('admin.bundles.index', compact('bundles'));
    }

    public function create()
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        return view('admin.bundles.form');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $validated = $this->validateBundle($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('bundles', 'public');
        }

        Bundle::create($validated);

        return redirect()
            ->route('admin.bundles.index')
            ->with('success', 'Bundle created successfully.');
    }

    public function edit(Bundle $bundle)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        return view('admin.bundles.form', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $validated = $this->validateBundle($request);

        if ($request->hasFile('image')) {
            if ($bundle->image) {
                Storage::disk('public')->delete($bundle->image);
            }

            $validated['image'] = $request->file('image')->store('bundles', 'public');
        } else {
            unset($validated['image']);
        }

        $bundle->update($validated);

        return redirect()
            ->route('admin.bundles.index')
            ->with('success', 'Bundle updated successfully.');
    }

    public function destroy(Bundle $bundle)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if ($bundle->image) {
            Storage::disk('public')->delete($bundle->image);
        }

        $bundle->delete();

        return redirect()
            ->route('admin.bundles.index')
            ->with('success', 'Bundle deleted successfully.');
    }

    private function validateBundle(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:wedding,short_film'],
            'description' => ['nullable', 'string'],
            'daily_rental_price' => ['nullable', 'numeric', 'min:0'],
            'hourly_rental_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'late_fee_per_day' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        return $validated;
    }
}
