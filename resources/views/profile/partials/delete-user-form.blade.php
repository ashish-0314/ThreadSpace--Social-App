<section class="space-y-6">
    <header>
        <h2 style="font-size:1.1rem;font-weight:700;color:#f0f6fc;">
            {{ __('Delete Account') }}
        </h2>

        <p style="font-size:.85rem;color:#8b949e;margin-top:4px;">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        class="btn-fill" style="background:#ef4444;margin-top:16px;padding:8px 24px;"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 style="font-size:1.1rem;font-weight:700;color:#f0f6fc;margin-bottom:8px;">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p style="font-size:.85rem;color:#8b949e;margin-bottom:16px;">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="ts-input"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="btn-outline" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="btn-fill" style="background:#ef4444;">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
