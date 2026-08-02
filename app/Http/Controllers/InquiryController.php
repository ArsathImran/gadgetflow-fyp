<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->check(), 403);

        $inquiries = auth()->user()
            ->inquiries()
            ->latest()
            ->paginate(10);

        return view('customer.inquiries.index', compact('inquiries'));
    }

    public function adminIndex(): View
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $inquiries = Inquiry::query()
            ->with('user')
            ->orderByRaw("status = 'open' desc")
            ->latest()
            ->paginate(15);

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry): View
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function reply(Request $request, Inquiry $inquiry): RedirectResponse
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:4000'],
        ]);

        $inquiry->update([
            'admin_reply' => $validated['admin_reply'],
            'status' => 'responded',
            'replied_at' => now(),
        ]);

        return redirect()
            ->route('admin.inquiries.show', $inquiry)
            ->with('success', 'Reply sent to ' . $inquiry->name . '.');
    }
}
