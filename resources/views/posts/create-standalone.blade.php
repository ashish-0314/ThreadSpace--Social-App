<x-app-layout>
<div style="max-width:680px;margin:0 auto;padding:32px 16px;" x-data="{ tab: '{{ old('type', 'text') }}' }">

    <!-- Header -->
    <div style="margin-bottom:24px;">
        <h1 style="font-size:1.3rem;font-weight:800;color:#f0f6fc;margin:0 0 4px;">✏️ Create a Post</h1>
        <p style="font-size:.85rem;color:#6b7280;margin:0;">Share something with the ThreadSpace community.</p>
    </div>

    <form action="{{ route('posts.store.standalone') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Community (optional) -->
        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.82rem;font-weight:600;color:#9ca3af;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">
                Post to Community <span style="font-size:.72rem;font-weight:400;color:#4b5563;">(optional)</span>
            </label>
            <select name="community_id" class="ts-input" style="cursor:pointer;">
                <option value="">🌐 No community — post independently</option>
                @foreach($communities as $community)
                    <option value="{{ $community->id }}" {{ old('community_id') === $community->id ? 'selected' : '' }}>
                        c/{{ $community->name }}
                    </option>
                @endforeach
            </select>
            @error('community_id')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <!-- Title -->
        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.82rem;font-weight:600;color:#9ca3af;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Title</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="ts-input" placeholder="An interesting title…" required>
            @error('title')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <!-- Flair / Intent -->
        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.82rem;font-weight:600;color:#9ca3af;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Flair</label>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @foreach(['Discussion','Question','Tutorial','Opinion'] as $intent)
                <label style="display:flex;align-items:center;gap:5px;cursor:pointer;padding:6px 14px;border-radius:999px;border:1px solid #30363d;font-size:.82rem;font-weight:600;color:#8b949e;transition:all .15s;"
                       onmouseover="this.style.borderColor='#58a6ff';this.style.color='#58a6ff'"
                       onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='#30363d';this.style.color='#8b949e'}">
                    <input type="radio" name="intent" value="{{ $intent }}"
                           {{ old('intent','Discussion') === $intent ? 'checked' : '' }}
                           style="display:none;">
                    {{ $intent }}
                </label>
                @endforeach
            </div>
            @error('intent')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <!-- Post type tabs -->
        <div style="background:#161b22;border:1px solid #21262d;border-radius:12px;overflow:hidden;margin-bottom:20px;" x-data="{ fileName: '' }">
            <!-- Tab bar -->
            <div style="display:flex;border-bottom:1px solid #21262d;">
                @foreach(['text' => '📝 Text', 'media' => '🖼️ Media', 'link' => '🔗 Link'] as $type => $label)
                <button type="button"
                        @click="tab = '{{ $type }}'"
                        :style="tab === '{{ $type }}' ? 'border-bottom:2px solid #58a6ff;color:#f0f6fc;' : 'border-bottom:2px solid transparent;color:#6b7280;'"
                        style="flex:1;padding:12px;background:transparent;border:none;border-top:none;border-left:none;border-right:none;font-size:.85rem;font-weight:600;cursor:pointer;transition:all .15s;">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            <input type="hidden" name="type" x-model="tab">

            <!-- Text -->
            <div x-show="tab === 'text'" style="padding:14px;">
                <textarea name="content" class="ts-input" rows="6"
                          placeholder="What's on your mind?" :disabled="tab !== 'text'">{{ old('content') }}</textarea>
                @error('content')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <!-- Media -->
            <div x-show="tab === 'media'" style="padding:14px;display:none;">
                <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;border:2px dashed #30363d;border-radius:10px;padding:32px;cursor:pointer;transition:border-color .2s;"
                       onmouseover="this.style.borderColor='#58a6ff'" onmouseout="this.style.borderColor='#30363d'">
                    <svg width="36" height="36" fill="none" stroke="#4b5563" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:12px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span style="font-size:.85rem;font-weight:600;color:#9ca3af;margin-bottom:4px;" x-text="fileName ? fileName : 'Click to upload media (up to 10 files)'"></span>
                    <span style="font-size:.75rem;color:#4b5563;" x-show="!fileName">Images, Video, Audio — max 20 MB each</span>
                    <input type="file" name="media[]" multiple accept="image/*,video/*,audio/*" style="display:none;" :disabled="tab !== 'media'"
                           @change="fileName = $event.target.files.length > 0 ? Array.from($event.target.files).map(f => f.name).join(', ') : ''">
                </label>
                @error('media')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <!-- Link -->
            <div x-show="tab === 'link'" style="padding:14px;display:none;">
                <input type="url" name="content" value="{{ old('content') }}"
                       class="ts-input" placeholder="https://…" :disabled="tab !== 'link'">
                @error('content')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Actions -->
        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ url()->previous() }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-fill" style="padding:8px 28px;">🚀 Post</button>
        </div>
    </form>
</div>
</x-app-layout>
