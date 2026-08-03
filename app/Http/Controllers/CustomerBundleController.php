<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerBundleController extends Controller
{
    public function index(Request $request): View|JsonResponse
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
            ->orderBy('name', 'asc')
            ->paginate(9)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('customer.bundles._grid-items', compact('bundles'))->render(),
                'next_page_url' => $bundles->nextPageUrl(),
                'count' => $bundles->count(),
                'total' => $bundles->total(),
            ]);
        }

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
