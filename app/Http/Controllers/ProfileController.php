<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validateWithBag('avatarUpdate', [
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Update the user's supporting ID document.
     */
    public function updateIdDocument(Request $request): RedirectResponse
    {
        $request->validateWithBag('idDocumentUpdate', [
            'id_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'],
        ]);

        $user = $request->user();

        if ($user->id_document_path) {
            Storage::disk('public')->delete($user->id_document_path);
        }

        $user->id_document_path = $request->file('id_document')->store('id-documents', 'public');
        $user->id_document_uploaded_at = now();
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'id-document-updated');
    }

    /**
     * Remove the user's supporting ID document.
     */
    public function destroyIdDocument(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->id_document_path) {
            Storage::disk('public')->delete($user->id_document_path);

            $user->id_document_path = null;
            $user->id_document_uploaded_at = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'id-document-removed');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
