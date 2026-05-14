<x-guest-layout>
    <div class="auth-card">
        <div class="auth-card-header">
            <h2>New Password</h2>
            <p>Create a secure password for your account.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" x-data="{ showPassword: false, showConfirm: false }">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <i class="fa-regular fa-envelope auth-input-icon"></i>
                    <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus placeholder="you@example.com" class="auth-input">
                    <label for="email" class="auth-input-label">Email Address</label>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Password -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <i class="fa-solid fa-lock auth-input-icon"></i>
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="••••••••" class="auth-input">
                    <label for="password" class="auth-input-label">Password</label>
                    <button type="button" @click="showPassword = !showPassword" class="password-toggle">
                        <i class="fa-regular" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Confirm Password -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <i class="fa-solid fa-lock auth-input-icon"></i>
                    <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="auth-input">
                    <label for="password_confirmation" class="auth-input-label">Confirm Password</label>
                    <button type="button" @click="showConfirm = !showConfirm" class="password-toggle">
                        <i class="fa-regular" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
            </div>

            <button type="submit" class="auth-btn-primary">
                Update Password <i class="fa-solid fa-key"></i>
            </button>
        </form>
    </div>
</x-guest-layout>
