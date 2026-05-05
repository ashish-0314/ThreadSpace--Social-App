<x-app-layout>
    <div style="max-width:760px;margin:0 auto;padding:24px 16px;">
        <h1 style="font-size:1.4rem;font-weight:800;color:#f0f6fc;margin-bottom:24px;">⚙️ Profile Settings</h1>

        <div class="post-card" style="margin-bottom:24px;cursor:default;">
            <div style="max-width:xl;">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="post-card" style="margin-bottom:24px;cursor:default;">
            <div style="max-width:xl;">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="post-card" style="margin-bottom:24px;cursor:default;border-color:rgba(239,68,68,.3);">
            <div style="max-width:xl;">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
