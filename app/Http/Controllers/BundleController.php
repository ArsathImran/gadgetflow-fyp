<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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

        $validated['gallery_images'] = $request->hasFile('gallery_images')
            ? collect($request->file('gallery_images'))
                ->map(fn ($file) => $file->store('bundles', 'public'))
                ->values()
                ->all()
            : null;

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

        $validated = $this->validateBundle($request, isEditing: true);

        $existingGalleryImages = collect($bundle->gallery_images ?? []);
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
            if ($bundle->image) {
                Storage::disk('public')->delete($bundle->image);
            }

            $validated['image'] = $request->file('image')->store('bundles', 'public');
        } else {
            unset($validated['image']);
        }

        if ($removedGalleryImages->isNotEmpty()) {
            Storage::disk('public')->delete($removedGalleryImages->all());
        }

        $validated['gallery_images'] = $remainingGalleryImages
            ->merge(
                $newGalleryImageFiles->map(fn ($file) => $file->store('bundles', 'public'))
            )
            ->values()
            ->all();

        unset($validated['remove_gallery_images']);

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

        if (! empty($bundle->gallery_images)) {
            Storage::disk('public')->delete($bundle->gallery_images);
        }

        $bundle->delete();

        return redirect()
            ->route('admin.bundles.index')
            ->with('success', 'Bundle deleted successfully.');
    }

    private function validateBundle(Request $request, bool $isEditing = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:wedding,short_film'],
            'description' => ['nullable', 'string'],
            'daily_rental_price' => ['nullable', 'numeric', 'min:0'],
            'hourly_rental_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'late_fee_per_day' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'gallery_images' => ['nullable', 'array', 'max:6'],
            'gallery_images.*' => ['image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ];

        if ($isEditing) {
            $rules['remove_gallery_images'] = ['nullable', 'array'];
            $rules['remove_gallery_images.*'] = ['string'];
        }

        return $request->validate($rules);
    }
}
