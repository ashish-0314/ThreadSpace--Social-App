<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->user();

            // Check if the user already exists in the database by email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // If they don't exist, create a new ThreadSpace account
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Google User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar_url' => $googleUser->getAvatar(),
                    // Generate a random password since they use Google
                    'password' => bcrypt(Str::random(16)), 
                ]);
            } else {
                // If they exist but don't have a google_id, link the account
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar_url' => $user->avatar_url ?? $googleUser->getAvatar()
                ]);
            }

            // Log the user in
            Auth::login($user, true);

            // Redirect to homepage
            return redirect()->route('home')->with('success', 'Logged in via Google successfully!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google. Error: ' . $e->getMessage());
        }
    }
}
