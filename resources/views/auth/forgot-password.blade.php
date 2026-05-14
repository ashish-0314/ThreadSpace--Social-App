<x-guest-layout>
    <div class="auth-card">
        <div class="auth-card-header">
            <h2>Reset Password</h2>
            <p>We'll email you a link to get back into your account.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <i class="fa-regular fa-envelope auth-input-icon"></i>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" class="auth-input">
                    <label for="email" class="auth-input-label">Email Address</label>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
            </div>

            <button type="submit" class="auth-btn-primary">
                Send Reset Link <i class="fa-solid fa-paper-plane"></i>
            </button>

            <div class="auth-footer">
                Remember your password? <a href="{{ route('login') }}">Log in</a>
            </div>
        </form>
    </div>
</x-guest-layout>
