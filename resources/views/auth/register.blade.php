<x-guest-layout>
    <div class="auth-card">
        <div class="auth-card-header">
            <h2>Create your account</h2>
            <p>Let's get you set up.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" x-data="{ showPassword: false, showConfirm: false }">
            @csrf

            <!-- Name -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <div class="auth-input-icon">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="auth-input-content">
                        <label for="name" class="auth-input-label">Full Name</label>
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Jane Doe" class="auth-input">
                    </div>
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Email Address -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <div class="auth-input-icon">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <div class="auth-input-content">
                        <label for="email" class="auth-input-label">Email Address</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" class="auth-input">
                    </div>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Password -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <div class="auth-input-icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="auth-input-content">
                        <label for="password" class="auth-input-label">Password</label>
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="••••••••" class="auth-input">
                    </div>
                    <button type="button" @click="showPassword = !showPassword" class="password-toggle">
                        <i class="fa-regular" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Confirm Password -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <div class="auth-input-icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="auth-input-content">
                        <label for="password_confirmation" class="auth-input-label">Confirm Password</label>
                        <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="auth-input">
                    </div>
                    <button type="button" @click="showConfirm = !showConfirm" class="password-toggle">
                        <i class="fa-regular" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
            </div>

            <button type="submit" class="auth-btn-primary" style="margin-top: 12px;">
                Create Account <i class="fa-solid fa-arrow-right"></i>
            </button>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
            </div>

            <div class="auth-divider">OR</div>

            <a href="/auth/google" class="auth-btn-google" style="text-decoration: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </a>
        </form>
    </div>
</x-guest-layout>
