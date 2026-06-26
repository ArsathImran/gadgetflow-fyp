<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GadgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $gadgets = Gadget::query()
            ->with('category')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('gadgets.index', compact('gadgets', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('gadgets.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'daily_rental_price' => ['required', 'numeric', 'min:0'],
            'hourly_rental_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gadgets', 'public');
        }

        Gadget::create($validated);

        return redirect()
            ->route('gadgets.index')
            ->with('success', 'Gadget created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gadget $gadget)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $gadget->load('category');

        return view('gadgets.show', compact('gadget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gadget $gadget)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('gadgets.form', compact('gadget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gadget $gadget)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'daily_rental_price' => ['required', 'numeric', 'min:0'],
            'hourly_rental_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($request->hasFile('image')) {
            if ($gadget->image) {
                Storage::disk('public')->delete($gadget->image);
            }

            $validated['image'] = $request->file('image')->store('gadgets', 'public');
        } else {
            unset($validated['image']);
        }

        $gadget->update($validated);

        return redirect()
            ->route('gadgets.index')
            ->with('success', 'Gadget updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gadget $gadget)
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if ($gadget->image) {
            Storage::disk('public')->delete($gadget->image);
        }

        $gadget->delete();

        return redirect()
            ->route('gadgets.index')
            ->with('success', 'Gadget deleted successfully.');
    }
}
