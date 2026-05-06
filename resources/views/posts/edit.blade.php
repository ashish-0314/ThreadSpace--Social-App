<x-app-layout>
<div style="max-width:680px;margin:40px auto;padding:0 16px;">

    <div style="background:#161b22;border:1px solid #30363d;border-radius:16px;padding:32px;">
        <h2 style="font-size:1.35rem;font-weight:800;color:#f0f6fc;margin-bottom:24px;">✏️ Edit Post</h2>

        @if($errors->any())
            <div style="background:#2d0e0e;border:1px solid #f85149;border-radius:10px;padding:14px;margin-bottom:20px;">
                @foreach($errors->all() as $error)
                    <p style="color:#f85149;font-size:.88rem;margin:2px 0;">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Title --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.85rem;font-weight:700;color:#8b949e;margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em;">Title</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                       style="width:100%;padding:12px 14px;background:#0d1117;border:1px solid #30363d;border-radius:10px;color:#f0f6fc;font-size:.95rem;outline:none;transition:border-color .2s;"
                       onfocus="this.style.borderColor='#2f81f7'" onblur="this.style.borderColor='#30363d'">
            </div>

            {{-- Intent --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.85rem;font-weight:700;color:#8b949e;margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em;">Intent / Flair</label>
                <select name="intent" required
                        style="width:100%;padding:12px 14px;background:#0d1117;border:1px solid #30363d;border-radius:10px;color:#f0f6fc;font-size:.95rem;outline:none;">
                    @foreach(['Question','Discussion','Tutorial','Opinion'] as $intent)
                        <option value="{{ $intent }}" {{ old('intent', $post->intent) === $intent ? 'selected' : '' }}>{{ $intent }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Content (only for text/link posts) --}}
            @if(in_array($post->type, ['text','link']))
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:.85rem;font-weight:700;color:#8b949e;margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em;">
                    {{ $post->type === 'link' ? 'Link URL' : 'Content' }}
                </label>
                <textarea name="content" rows="6"
                          style="width:100%;padding:12px 14px;background:#0d1117;border:1px solid #30363d;border-radius:10px;color:#f0f6fc;font-size:.92rem;line-height:1.7;resize:vertical;outline:none;transition:border-color .2s;"
                          onfocus="this.style.borderColor='#2f81f7'" onblur="this.style.borderColor='#30363d'"
                          placeholder="{{ $post->type === 'link' ? 'https://...' : 'Write something...' }}">{{ old('content', $post->content) }}</textarea>
            </div>
            @endif

            {{-- Note for media posts --}}
            @if($post->type === 'media')
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:.85rem;font-weight:700;color:#8b949e;margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em;">Current Media</label>

                @if(!empty($post->media))
                <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
                    @foreach($post->media as $index => $media)
                    <div x-data="{ marked: false }" style="position:relative;border-radius:10px;overflow:hidden;border:1px solid #30363d;"
                         :style="marked ? 'border-color:#f85149;opacity:.6;' : ''">
                        @if($media['type'] === 'image')
                            <img src="{{ $media['url'] }}" style="width:120px;height:100px;object-fit:cover;display:block;" alt="Media {{ $index + 1 }}">
                        @elseif($media['type'] === 'video')
                            <video src="{{ $media['url'] }}" style="width:120px;height:100px;object-fit:cover;display:block;"></video>
                        @endif
                        <button type="button" @click="marked=!marked"
                                :style="marked ? 'background:rgba(218,54,51,1);' : 'background:rgba(218,54,51,.75);'"
                                style="position:absolute;top:6px;right:6px;border-radius:50%;width:26px;height:26px;display:flex;align-items:center;justify-content:center;font-size:.8rem;border:none;cursor:pointer;"
                                :title="marked ? 'Click to keep' : 'Click to delete'">
                            <span x-text="marked ? '✕' : '🗑'"></span>
                        </button>
                        <input type="checkbox" name="delete_media[]" value="{{ $index }}" x-model="marked" style="display:none;">
                        <div x-show="marked" style="position:absolute;bottom:0;left:0;right:0;background:rgba(218,54,51,.7);padding:3px 6px;font-size:.7rem;color:white;text-align:center;font-weight:700;">Will be removed</div>
                    </div>
                    @endforeach
                </div>
                <p style="font-size:.8rem;color:#6b7280;margin-bottom:16px;">Click the 🗑 icon on any image to mark it for removal, then save changes.</p>
                @endif

                <label style="display:block;font-size:.85rem;font-weight:700;color:#8b949e;margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em;">Add New Media</label>
                <input type="file" name="new_media[]" multiple accept="image/*,video/*,audio/*"
                       style="width:100%;padding:12px;background:#0d1117;border:2px dashed #30363d;border-radius:10px;color:#8b949e;font-size:.88rem;cursor:pointer;">
                <p style="font-size:.78rem;color:#484f58;margin-top:6px;">Supports JPG, PNG, GIF, MP4, MOV (max 20MB each).</p>
            </div>
            @endif

            <div style="display:flex;gap:12px;">
                <button type="submit"
                        style="padding:12px 28px;border-radius:10px;background:#2f81f7;border:none;color:white;font-weight:700;font-size:.95rem;cursor:pointer;transition:background .2s;"
                        onmouseover="this.style.background='#1f6feb'" onmouseout="this.style.background='#2f81f7'">
                    Save Changes
                </button>
                <a href="{{ route('posts.show', $post) }}"
                   style="padding:12px 24px;border-radius:10px;background:#21262d;border:1px solid #30363d;color:#d4d9e0;font-weight:700;font-size:.95rem;text-decoration:none;display:inline-flex;align-items:center;"
                   onmouseover="this.style.background='#30363d'" onmouseout="this.style.background='#21262d'">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>
</x-app-layout>
