<section>
    <header>
        <h2 style="font-size:1.1rem;font-weight:700;color:#f0f6fc;">
            {{ __('Update Password') }}
        </h2>

        <p style="font-size:.85rem;color:#8b949e;margin-top:4px;">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" style="display:block;font-size:.85rem;font-weight:600;color:#c9d1d9;margin-bottom:6px;">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="ts-input" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" style="display:block;font-size:.85rem;font-weight:600;color:#c9d1d9;margin-bottom:6px;">New Password</label>
            <input id="update_password_password" name="password" type="password" class="ts-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" style="display:block;font-size:.85rem;font-weight:600;color:#c9d1d9;margin-bottom:6px;">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="ts-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button class="btn-fill" style="padding:8px 24px;">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
