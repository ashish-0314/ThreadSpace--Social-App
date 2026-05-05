<x-guest-layout>
    <div style="background:white;padding:48px;border-radius:24px;box-shadow:0 20px 40px -10px rgba(0,0,0,0.1);">
        <div style="text-align:center;margin-bottom:32px;">
            <h2 style="font-size:2rem;font-weight:800;color:#111827;margin-bottom:8px;">Welcome back!</h2>
            <p style="color:#6b7280;font-size:1rem;">Log into your ThreadSpace account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div style="margin-bottom:20px;">
                <label for="email" style="display:block;font-size:.9rem;font-weight:700;color:#374151;margin-bottom:8px;">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                       style="width:100%;padding:14px 16px;border-radius:12px;border:1px solid #d1d5db;background:#f9fafb;font-size:1rem;color:#111827;outline:none;transition:border-color .2s;" 
                       onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#d1d5db'"
                       placeholder="you@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Password -->
            <div style="margin-bottom:24px;">
                <label for="password" style="display:block;font-size:.9rem;font-weight:700;color:#374151;margin-bottom:8px;">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                       style="width:100%;padding:14px 16px;border-radius:12px;border:1px solid #d1d5db;background:#f9fafb;font-size:1rem;color:#111827;outline:none;transition:border-color .2s;"
                       onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#d1d5db'"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                
                @if (Route::has('password.request'))
                    <div style="text-align:right;margin-top:8px;">
                        <a href="{{ route('password.request') }}" style="font-size:.85rem;color:#4f46e5;font-weight:600;text-decoration:none;">Forgot Password?</a>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" style="width:100%;padding:14px;border-radius:12px;background:#4f46e5;color:white;font-weight:700;font-size:1.05rem;border:none;cursor:pointer;margin-bottom:20px;transition:background .2s;box-shadow:0 4px 6px -1px rgba(79, 70, 229, 0.3);" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
                Log In
            </button>

            <!-- Remember Me -->
            <div style="display:flex;align-items:center;margin-bottom:24px;">
                <input id="remember_me" type="checkbox" name="remember" style="width:18px;height:18px;border-radius:4px;border-color:#d1d5db;color:#4f46e5;">
                <label for="remember_me" style="margin-left:8px;font-size:.95rem;color:#4b5563;">Keep me logged in</label>
            </div>

            <!-- Sign Up Link -->
            <div style="text-align:center;font-size:.95rem;color:#4b5563;margin-bottom:24px;">
                Don't have an account? <a href="{{ route('register') }}" style="color:#4f46e5;font-weight:700;text-decoration:none;">Sign Up Now</a>
            </div>

            <!-- Social Logins -->
            <div style="text-align:center;font-size:.95rem;color:#4b5563;display:flex;align-items:center;justify-content:center;gap:12px;">
                <span>Or log in with:</span>
                <a href="/auth/google" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:#f3f4f6;color:#1f2937;text-decoration:none;transition:background .2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
