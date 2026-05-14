<section>
    <header style="margin-bottom:32px;">
        <h2 style="font-size:1.4rem;font-weight:800;color:#f0f6fc;letter-spacing:-0.5px;">
            {{ __('Profile Information') }}
        </h2>

        <p style="font-size:.95rem;color:#8b949e;margin-top:8px;">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" style="display:flex;flex-direction:column;gap:24px;" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <label for="name" style="display:block;font-size:.9rem;font-weight:700;color:#c9d1d9;margin-bottom:8px;">Name</label>
            <input id="name" name="name" type="text" class="ts-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" style="width:100%;font-size:1rem;padding:12px 16px;border-radius:12px;" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" style="display:block;font-size:.9rem;font-weight:700;color:#c9d1d9;margin-bottom:8px;">Email</label>
            <input id="email" name="email" type="email" class="ts-input" value="{{ old('email', $user->email) }}" required autocomplete="username" style="width:100%;font-size:1rem;padding:12px 16px;border-radius:12px;" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="bio" style="display:block;font-size:.9rem;font-weight:700;color:#c9d1d9;margin-bottom:8px;">Bio</label>
            <textarea id="bio" name="bio" class="ts-input" rows="4" style="width:100%;font-size:1rem;padding:12px 16px;border-radius:12px;">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="mt-8 pt-6" style="border-top:1px solid #21262d;" x-data="{ avatarType: 'default', selectedAvatar: '{{ $user->avatar_url }}', previewUrl: null }">
            <h3 style="font-size:1.1rem;font-weight:700;color:#f0f6fc;margin-bottom:16px;">Profile Picture</h3>
            
            <div class="flex items-center gap-6 mb-6">
                <!-- Current Avatar Preview -->
                <div class="shrink-0">
                    <template x-if="previewUrl">
                        <img :src="previewUrl" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #58a6ff;">
                    </template>
                    <template x-if="!previewUrl && selectedAvatar">
                        <img :src="selectedAvatar" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #30363d;">
                    </template>
                    <template x-if="!previewUrl && !selectedAvatar">
                        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;font-weight:700;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </template>
                </div>
                
                <!-- Toggle -->
                <div class="space-y-2">
                    <label class="inline-flex items-center">
                        <input type="radio" x-model="avatarType" value="default" class="text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Choose Default Avatar</span>
                    </label>
                    <br>
                    <label class="inline-flex items-center">
                        <input type="radio" x-model="avatarType" value="upload" class="text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Upload Custom Image</span>
                    </label>
                </div>
            </div>

            <!-- Default Avatars -->
            <div x-show="avatarType === 'default'" style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
                <input type="hidden" name="default_avatar" :value="avatarType === 'default' ? selectedAvatar : ''">
                @php
                    $avatars = glob(public_path('avatars/*.{png,jpg,jpeg,gif}'), GLOB_BRACE);
                @endphp
                @foreach($avatars as $avatarPath)
                    @php $avatarUrl = asset('avatars/' . basename($avatarPath)); @endphp
                    <button type="button" 
                            @click="selectedAvatar = '{{ $avatarUrl }}'; previewUrl = null;"
                            :style="selectedAvatar === '{{ $avatarUrl }}' ? 'border:2px solid #58a6ff;opacity:1;' : 'border:2px solid transparent;opacity:0.7;'"
                            style="border-radius:50%;overflow:hidden;transition:all 0.2s;background:none;padding:0;cursor:pointer;"
                            onmouseover="this.style.opacity='1'"
                            onmouseout="if(selectedAvatar !== '{{ $avatarUrl }}') this.style.opacity='0.7'">
                        <img src="{{ $avatarUrl }}" style="width:48px;height:48px;object-fit:cover;display:block;">
                    </button>
                @endforeach
            </div>

            <!-- Upload Custom -->
            <div x-show="avatarType === 'upload'" class="mb-4">
                <input type="file" name="custom_avatar" accept="image/*"
                       @change="previewUrl = URL.createObjectURL($event.target.files[0]); selectedAvatar = null;"
                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG up to 5MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('custom_avatar')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button class="btn-fill" style="padding:8px 24px;">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
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
