<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $inquiry = Inquiry::create([
            'user_id' => auth()->id(),
            ...$validated,
        ]);

        return redirect()
            ->route('customer.inquiries.index')
            ->with('success', 'Thanks, we\'ll get back to you soon.')
            ->with('new_inquiry_id', $inquiry->id);
    }
}
