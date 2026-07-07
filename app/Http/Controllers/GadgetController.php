<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'daily_rental_price' => ['required', 'numeric', 'min:0'],
            'hourly_rental_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
            'late_fee_per_day' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => ['image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'condition' => ['required', 'in:new,like_new,good,fair'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gadgets', 'public');
        }

        $validated['gallery_images'] = $request->hasFile('gallery_images')
            ? collect($request->file('gallery_images'))
                ->map(fn ($file) => $file->store('gadgets', 'public'))
                ->values()
                ->all()
            : null;

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
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'daily_rental_price' => ['required', 'numeric', 'min:0'],
            'hourly_rental_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
            'late_fee_per_day' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => ['image', 'max:2048'],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['string'],
            'status' => ['required', 'in:active,inactive'],
            'condition' => ['required', 'in:new,like_new,good,fair'],
        ]);

        $existingGalleryImages = collect($gadget->gallery_images ?? []);
        $removedGalleryImages = collect($validated['remove_gallery_images'] ?? [])
            ->filter(fn ($path) => $existingGalleryImages->contains($path))
            ->values();
        $remainingGalleryImages = $existingGalleryImages
            ->reject(fn ($path) => $removedGalleryImages->contains($path))
            ->values();
        $newGalleryImageFiles = collect($request->file('gallery_images', []));

        if ($remainingGalleryImages->count() + $newGalleryImageFiles->count() > 6) {
            throw ValidationException::withMessages([
                'gallery_images' => 'You may keep up to 6 gallery images in total.',
            ]);
        }

        if ($request->hasFile('image')) {
            if ($gadget->image) {
                Storage::disk('public')->delete($gadget->image);
            }

            $validated['image'] = $request->file('image')->store('gadgets', 'public');
        } else {
            unset($validated['image']);
        }

        if ($removedGalleryImages->isNotEmpty()) {
            Storage::disk('public')->delete($removedGalleryImages->all());
        }

        $validated['gallery_images'] = $remainingGalleryImages
            ->merge(
                $newGalleryImageFiles->map(fn ($file) => $file->store('gadgets', 'public'))
            )
            ->values()
            ->all();

        unset($validated['remove_gallery_images']);

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

        if (!empty($gadget->gallery_images)) {
            Storage::disk('public')->delete($gadget->gallery_images);
        }

        $gadget->delete();

        return redirect()
            ->route('gadgets.index')
            ->with('success', 'Gadget deleted successfully.');
    }
}
