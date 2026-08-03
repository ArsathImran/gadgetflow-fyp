<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Http\Request;

class CustomerGadgetController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->whereHas('gadgets', function ($query) {
                $query->where('status', 'active')
                    ->where('quantity', '>', 0);
            })
            ->orderBy('name')
            ->get();

        $gadgets = Gadget::query()
            ->with('category')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('status', 'active')
            ->when($request->get('availability') !== 'any', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->when($request->filled('price_range'), function ($query) use ($request) {
                match ($request->string('price_range')->toString()) {
                    'under_50' => $query->where('daily_rental_price', '<', 50),
                    '50_150' => $query->whereBetween('daily_rental_price', [50, 150]),
                    '150_plus' => $query->where('daily_rental_price', '>', 150),
                    default => $query,
                };
            })
            ->orderBy('name', 'asc')
            ->paginate(12)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('customer.gadgets._grid-items', compact('gadgets'))->render(),
                'next_page_url' => $gadgets->nextPageUrl(),
                'count' => $gadgets->count(),
                'total' => $gadgets->total(),
            ]);
        }

        $featuredGadget = Gadget::query()
            ->with('category')
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->whereNotNull('image')
            ->latest()
            ->first();

        return view('customer.gadgets.index', compact('gadgets', 'categories', 'featuredGadget'));
    }

    public function show(Gadget $gadget)
    {
        abort_unless($gadget->status === 'active' && $gadget->quantity > 0, 404);

        $gadget->load([
            'category',
            'reviews' => fn ($query) => $query->with('user')->latest()->limit(10),
        ])->loadCount('reviews')->loadAvg('reviews', 'rating');

        return view('customer.gadgets.show', compact('gadget'));
    }
}
