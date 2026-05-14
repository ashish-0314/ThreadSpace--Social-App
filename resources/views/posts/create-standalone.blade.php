<x-app-layout>
<style>
    body {
        background: #050816 url('{{ asset('images/thbg2.png') }}') no-repeat center center fixed !important;
        background-size: cover !important;
    }
    .create-split-container {
        display: flex;
        max-width: 1000px;
        width: 100%;
        background: #0d1117;
        border: 1px solid #30363d;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }
    .create-left {
        flex: 1;
        position: relative;
        display: none;
    }
    @media(min-width: 860px) {
        .create-left {
            display: block;
        }
    }
    .create-right {
        flex: 1.2;
        padding: 32px;
        display: flex;
        flex-direction: column;
    }
    @media(max-width: 600px) {
        .create-right {
            padding: 32px;
        }
    }
    .create-input {
        width: 100%;
        background: #050816;
        border: 1px solid #30363d;
        border-radius: 8px;
        padding: 10px 14px;
        color: #e1e4e8;
        font-size: 0.9rem;
        font-weight: 400;
        outline: none;
        transition: all 0.2s ease;
    }
    .create-input:focus {
        border-color: #58a6ff;
        background: #161b22;
    }
    .create-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
        color: #6e7681;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    .flair-radio:checked + label {
        border-color: #58a6ff;
        color: #58a6ff;
        background: rgba(88, 166, 255, 0.05);
    }
    .submit-btn {
        background: #f0f6fc;
        color: #050816;
        border: none;
        padding: 10px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s;
    }
    .submit-btn:hover {
        opacity: 0.85;
    }
</style>

<div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 75px); padding: 40px 20px;" x-data="{ tab: '{{ old('type', 'text') }}' }">
    
    <div class="create-split-container">
        <!-- Left Side Image -->
        <div class="create-left">
            <img src="{{ asset('images/create_post_visual.png') }}" style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0;" alt="Create a Post">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(13, 17, 23, 0.9) 0%, transparent 80%); display: flex; flex-direction: column; justify-content: flex-end; padding: 32px;">
                <h2 style="color: rgba(255,255,255,0.9); font-size: 1.8rem; font-weight: 400; line-height: 1.2; margin-bottom: 8px; letter-spacing: -0.5px;">Share your universe.</h2>
                <p style="color: rgba(255,255,255,0.5); font-size: 0.95rem; font-weight: 300; line-height: 1.5; margin: 0;">Ignite a conversation or share an opinion.</p>
            </div>
        </div>

        <!-- Right Side Form -->
        <div class="create-right">
            <div style="margin-bottom: 20px;">
                <h1 style="font-size: 1.4rem; font-weight: 500; color: #e1e4e8; margin: 0 0 6px; letter-spacing: -0.3px;">Create a Post</h1>
                <p style="font-size: 0.85rem; font-weight: 400; color: #6e7681; margin: 0;">Post independently or select a specific community.</p>
            </div>

            <form action="{{ route('posts.store.standalone') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf

                <!-- Community (optional) -->
                <div>
                    <label class="create-label">Post to Community <span style="font-size:.72rem;font-weight:400;color:#4b5563;text-transform:none;">(optional)</span></label>
                    <select name="community_id" class="create-input" style="cursor:pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%238b949e%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 16px top 50%; background-size: 12px auto;">
                        <option value="" style="background: #161b22;">🌐 No community — post independently</option>
                        @foreach($communities as $community)
                            <option value="{{ $community->id }}" {{ old('community_id') === $community->id ? 'selected' : '' }} style="background: #161b22;">
                                c/{{ $community->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('community_id')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <!-- Title -->
                <div>
                    <label class="create-label">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="create-input" placeholder="An interesting title…" required>
                    @error('title')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <!-- Flair / Intent -->
                <div>
                    <label class="create-label">Flair</label>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        @foreach(['Discussion','Question','Tutorial','Opinion'] as $intent)
                        <div style="position: relative;">
                            <input type="radio" id="intent_{{ $intent }}" name="intent" value="{{ $intent }}" class="flair-radio" {{ old('intent','Discussion') === $intent ? 'checked' : '' }} style="position:absolute; opacity:0; width:0; height:0;">
                            <label for="intent_{{ $intent }}" style="display:inline-flex;align-items:center;cursor:pointer;padding:6px 14px;border-radius:6px;border:1px solid #30363d;font-size:.8rem;font-weight:400;color:#8b949e;transition:all .2s;background:#050816;">
                                {{ $intent }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('intent')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <!-- Post type tabs -->
                <div style="background: #161b22; border: 1px solid #30363d; border-radius: 8px; overflow: hidden;" x-data="{ fileName: '' }">
                    <!-- Tab bar -->
                    <div style="display:flex; border-bottom: 1px solid #30363d;">
                        @foreach(['text' => 'Text', 'media' => 'Media', 'link' => 'Link'] as $type => $label)
                        <button type="button"
                                @click="tab = '{{ $type }}'"
                                :style="tab === '{{ $type }}' ? 'border-bottom: 1px solid #58a6ff; color: #c9d1d9; background: #0d1117;' : 'border-bottom: 1px solid transparent; color: #6e7681;'"
                                style="flex:1; padding: 10px; border: none; font-size: 0.85rem; font-weight: 400; cursor: pointer; transition: all 0.2s; background: transparent;">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>

                    <input type="hidden" name="type" x-model="tab">

                    <!-- Text -->
                    <div x-show="tab === 'text'" style="padding: 12px;">
                        <textarea name="content" class="create-input" rows="4" placeholder="What's on your mind?" :disabled="tab !== 'text'" style="resize: vertical; min-height: 80px;"></textarea>
                        @error('content')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    <!-- Media -->
                    <div x-show="tab === 'media'" style="padding: 12px; display:none;">
                        <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;border:1px dashed #30363d;border-radius:8px;padding:24px 20px;cursor:pointer;transition:all .2s; background: #0d1117;"
                               onmouseover="this.style.borderColor='#58a6ff'; this.style.background='#161b22'" onmouseout="this.style.borderColor='#30363d'; this.style.background='#0d1117'">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 1.5rem; color: #58a6ff; margin-bottom: 10px; opacity: 0.7;"></i>
                            <span style="font-size:.85rem;font-weight:400;color:#8b949e;margin-bottom:4px;text-align:center;" x-text="fileName ? fileName : 'Click to upload media files'"></span>
                            <span style="font-size:.7rem;color:#6e7681;text-align:center;" x-show="!fileName">Images, Video, Audio — max 20 MB each</span>
                            <input type="file" name="media[]" multiple accept="image/*,video/*,audio/*" style="display:none;" :disabled="tab !== 'media'"
                                   @change="fileName = $event.target.files.length > 0 ? Array.from($event.target.files).map(f => f.name).join(', ') : ''">
                        </label>
                        @error('media')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>

                    <!-- Link -->
                    <div x-show="tab === 'link'" style="padding: 12px; display:none;">
                        <input type="url" name="content" value="{{ old('content') }}" class="create-input" placeholder="https://…" :disabled="tab !== 'link'">
                        @error('content')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Actions -->
                <div style="display:flex;justify-content:flex-end;align-items:center;gap:16px;margin-top:4px;">
                    <a href="{{ url()->previous() }}" style="color: #8b949e; font-weight: 600; font-size: 0.95rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#f0f6fc'" onmouseout="this.style.color='#8b949e'">Cancel</a>
                    <button type="submit" class="submit-btn">Publish Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
