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
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search') . '%');
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('customer.gadgets.index', compact('gadgets', 'categories'));
    }

    public function show(Gadget $gadget)
    {
        abort_unless($gadget->status === 'active' && $gadget->quantity > 0, 404);

        $gadget->load('category');

        return view('customer.gadgets.show', compact('gadget'));
    }
}
