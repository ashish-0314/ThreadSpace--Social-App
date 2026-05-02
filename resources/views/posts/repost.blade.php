<x-app-layout>
<div style="max-width:680px;margin:0 auto;padding:32px 16px;">

    <!-- Header -->
    <div style="margin-bottom:24px;">
        <a href="{{ route('posts.show', $original) }}" style="font-size:.82rem;color:#6b7280;text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:12px;" onmouseover="this.style.color='#58a6ff'" onmouseout="this.style.color='#6b7280'">
            ← Back to original post
        </a>
        <h1 style="font-size:1.3rem;font-weight:800;color:#f0f6fc;margin:0;">↺ Repost</h1>
        <p style="font-size:.85rem;color:#6b7280;margin:4px 0 0;">Edit the title, add your comment, then choose where to post it.</p>
    </div>

    <!-- Original Post Preview -->
    <div style="background:#0d1117;border:1px solid #30363d;border-radius:12px;padding:14px 16px;margin-bottom:24px;">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;margin-bottom:10px;">
            Original Post
        </div>
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px;">
            <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;font-size:.62rem;font-weight:800;color:#fff;">
                {{ strtoupper(substr($original->user->name ?? 'U', 0, 1)) }}
            </div>
            <span style="font-size:.8rem;font-weight:600;color:#d4d9e0;">u/{{ $original->user->name ?? 'Unknown' }}</span>
            <span style="color:#374151;font-size:.75rem;">•</span>
            <span style="font-size:.76rem;color:#6b7280;">{{ $original->created_at->diffForHumans() }}</span>
        </div>
        <div style="font-size:.95rem;font-weight:700;color:#f0f6fc;margin-bottom:6px;">{{ $original->title }}</div>
        @if($original->type === 'text' && $original->content)
            <div style="font-size:.84rem;color:#8b949e;line-height:1.6;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                {{ $original->content }}
            </div>
        @elseif($original->type === 'image' && $original->image_url)
            <img src="{{ $original->image_url }}" style="width:100%;max-height:200px;object-fit:cover;border-radius:8px;">
        @elseif($original->type === 'link' && $original->content)
            <a href="{{ $original->content }}" target="_blank" style="font-size:.82rem;color:#58a6ff;word-break:break-all;">🔗 {{ $original->content }}</a>
        @endif
    </div>

    <!-- Repost Form -->
    <form action="{{ route('posts.repost.store', $original) }}" method="POST">
        @csrf

        <!-- Community -->
        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.82rem;font-weight:600;color:#9ca3af;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Post to Community</label>
            <select name="community_id" required class="ts-input" style="cursor:pointer;">
                <option value="">Select a community…</option>
                @foreach($communities as $community)
                    <option value="{{ $community->id }}">c/{{ $community->name }}</option>
                @endforeach
            </select>
            @error('community_id')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <!-- Title (editable) -->
        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.82rem;font-weight:600;color:#9ca3af;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Title <span style="font-size:.72rem;font-weight:400;color:#4b5563;">(you can edit this)</span></label>
            <input type="text" name="title" value="{{ old('title', $original->title) }}"
                   class="ts-input" style="resize:none;" required>
            @error('title')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <!-- Your comment (optional) -->
        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.82rem;font-weight:600;color:#9ca3af;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Your Comment <span style="font-size:.72rem;font-weight:400;color:#4b5563;">(optional)</span></label>
            <textarea name="repost_comment" class="ts-input" rows="3"
                      placeholder="Add your thoughts on this post…">{{ old('repost_comment') }}</textarea>
        </div>

        <!-- Intent -->
        <div style="margin-bottom:24px;">
            <label style="display:block;font-size:.82rem;font-weight:600;color:#9ca3af;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Flair</label>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @foreach(['Question','Discussion','Tutorial','Opinion'] as $intent)
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:6px 14px;border-radius:999px;border:1px solid #30363d;font-size:.82rem;font-weight:600;color:#8b949e;transition:all .15s;"
                       onmouseover="this.style.borderColor='#58a6ff';this.style.color='#58a6ff'"
                       onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='#30363d';this.style.color='#8b949e'}">
                    <input type="radio" name="intent" value="{{ $intent }}"
                           {{ old('intent', $original->intent) === $intent ? 'checked' : '' }}
                           style="display:none;"
                           onchange="document.querySelectorAll('.intent-label').forEach(l=>l.setAttribute('data-selected','0'));this.closest('label').setAttribute('data-selected','1')">
                    {{ $intent }}
                </label>
                @endforeach
            </div>
            @error('intent')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('posts.show', $original) }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-fill" style="padding:8px 24px;">↺ Repost</button>
        </div>
    </form>
</div>
</x-app-layout>
