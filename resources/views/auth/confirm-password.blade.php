<x-guest-layout>
    <div class="auth-card">
        <div class="auth-card-header">
            <h2>Secure Area</h2>
            <p>Please confirm your password before continuing.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" x-data="{ showPassword: false }">
            @csrf

            <!-- Password -->
            <div class="auth-form-group">
                <div class="auth-input-wrapper">
                    <i class="fa-solid fa-lock auth-input-icon"></i>
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••" class="auth-input">
                    <label for="password" class="auth-input-label">Password</label>
                    <button type="button" @click="showPassword = !showPassword" class="password-toggle">
                        <i class="fa-regular" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
            </div>

            <button type="submit" class="auth-btn-primary">
                Confirm <i class="fa-solid fa-check-circle"></i>
            </button>
        </form>
    </div>
</x-guest-layout>
