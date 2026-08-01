<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Gadget;
use App\Models\Rental;
use App\Models\Review;
use App\Models\User;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $stats = [
            'activeGadgets' => Gadget::where('status', 'active')->where('quantity', '>', 0)->count(),
            'completedRentals' => Rental::where('status', 'completed')->count(),
            'comboPackages' => Bundle::where('status', 'active')->count(),
            'registeredCustomers' => User::where('role', 'customer')->count(),
        ];

        $testimonials = Review::query()
            ->with(['user', 'gadget', 'bundle'])
            ->where('rating', '>=', 4)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->latest()
            ->take(3)
            ->get();

        return view('about', compact('stats', 'testimonials'));
    }
}
