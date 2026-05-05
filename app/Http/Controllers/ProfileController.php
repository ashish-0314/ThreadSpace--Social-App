<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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

        if ($request->has('bio')) {
            $request->user()->bio = $request->input('bio');
        }

        $request->validate([
            'default_avatar' => ['nullable', 'string'],
            'custom_avatar' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('custom_avatar')) {
            try {
                $imageKit = new \ImageKit\ImageKit(
                    config('imagekit.public_key'),
                    config('imagekit.private_key'),
                    config('imagekit.url_endpoint')
                );
                
                $file = $request->file('custom_avatar');
                $uploadResponse = $imageKit->uploadFile([
                    'file'     => base64_encode(file_get_contents($file->path())),
                    'fileName' => time() . '_' . $file->getClientOriginalName(),
                    'folder'   => '/threadspace_avatars',
                ]);

                if (isset($uploadResponse->result->url)) {
                    $request->user()->avatar_url = $uploadResponse->result->url;
                }
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['custom_avatar' => 'Failed to upload custom avatar: ' . $e->getMessage()]);
            }
        } elseif ($request->filled('default_avatar')) {
            $request->user()->avatar_url = $request->input('default_avatar');
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
