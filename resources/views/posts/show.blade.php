<x-app-layout>
<div style="max-width:760px;margin:0 auto;padding:24px 16px;" x-data="postView()">

    <!-- Full Post -->
    <div class="post-card" style="margin-bottom:16px;cursor:default;">
        <!-- Header -->
        <div class="pc-header">
            <div class="pc-avatar">{{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}</div>
            <span class="pc-author">u/{{ $post->user->name ?? 'Unknown' }}</span>
            <span class="pc-dot">•</span>
            <span class="pc-time">{{ $post->created_at->diffForHumans() }}</span>
            @if($post->community)
                <span class="pc-dot">·</span>
                <a href="{{ route('communities.show', $post->community->slug) }}" class="pc-comm">c/{{ $post->community->name }}</a>
            @endif
        </div>

        <!-- Title -->
        <h1 style="font-size:1.15rem;font-weight:700;color:#f0f6fc;line-height:1.4;margin:0 0 8px;">{{ $post->title }}</h1>

        <!-- Flair -->
        <div style="margin-bottom:12px;">
            <span class="pc-flair pc-flair-{{ $post->intent }}">{{ $post->intent }}</span>
        </div>

        <!-- Body -->
        <div style="margin-bottom:16px;">
            @if($post->type === 'text' && $post->content)
                <div style="font-size:.9rem;color:#d4d9e0;line-height:1.75;white-space:pre-wrap;">{!! nl2br(e($post->content)) !!}</div>
            @elseif($post->type === 'image' && $post->image_url)
                <img src="{{ $post->image_url }}" alt="Post image" style="width:100%;border-radius:8px;max-height:500px;object-fit:cover;">
            @elseif($post->type === 'link' && $post->content)
                <a href="{{ $post->content }}" target="_blank" class="text-link" style="font-size:.88rem;word-break:break-all;">🔗 {{ $post->content }}</a>
            @endif
        </div>

        <!-- AI Summary -->
        <div style="background:#0d1117;border:1px solid #21262d;border-radius:10px;padding:12px 14px;margin-bottom:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
                <span style="font-size:.82rem;font-weight:600;color:#8b949e;display:flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" fill="none" stroke="#8b949e" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    AI Thread Summary
                </span>
                <button @click="summarize('{{ route('posts.summarize', $post) }}')" :disabled="loading" class="btn-fill" style="font-size:.75rem;padding:4px 12px;">
                    <span x-show="!loading">✨ Generate</span>
                    <span x-show="loading">...</span>
                </button>
            </div>
            <div x-show="summary" x-transition style="margin-top:10px;font-size:.85rem;color:#9ca3af;line-height:1.7;font-style:italic;" x-text="summary"></div>
        </div>

        <!-- Action Bar -->
        <div class="pc-actions">
            @auth
            <form action="{{ route('vote') }}" method="POST" style="display:inline-flex;align-items:center;">
                @csrf
                <input type="hidden" name="votable_id"   value="{{ $post->id }}">
                <input type="hidden" name="votable_type" value="Post">
                <input type="hidden" name="value" id="vv-show-{{ $post->id }}" value="1">
                <div class="vote-pill">
                    <button type="submit" class="vote-btn-up" title="Upvote"
                            onclick="document.getElementById('vv-show-{{ $post->id }}').value='1'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <span class="vote-score">{{ (int)($post->upvotes ?? 0) - (int)($post->downvotes ?? 0) }}</span>
                    <div class="vote-divider"></div>
                    <button type="submit" class="vote-btn-down" title="Downvote"
                            onclick="document.getElementById('vv-show-{{ $post->id }}').value='-1'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
            </form>
            @else
            <a href="{{ route('login') }}" class="vote-pill" style="text-decoration:none;">
                <span class="vote-btn-up" style="cursor:pointer;"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg></span>
                <span class="vote-score">{{ (int)($post->upvotes ?? 0) - (int)($post->downvotes ?? 0) }}</span>
                <div class="vote-divider"></div>
                <span class="vote-btn-down" style="cursor:pointer;"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
            </a>
            @endauth

            {{-- Share dropdown --}}
            <div style="position:relative;" x-data="{ shareOpen: false }" @click.outside="shareOpen=false">
                <button class="act-pill" @click="shareOpen=!shareOpen" style="border:none;">
                    ↗ Share
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="shareOpen" x-transition
                     style="position:absolute;bottom:calc(100% + 6px);left:0;background:#161b22;border:1px solid #30363d;border-radius:10px;padding:4px;min-width:180px;z-index:50;box-shadow:0 8px 24px rgba(0,0,0,.5);">
                    <button onclick="navigator.clipboard.writeText(window.location.href).then(()=>alert('Link copied!'))"
                            style="display:flex;align-items:center;gap:8px;width:100%;padding:8px 12px;background:transparent;border:none;border-radius:7px;cursor:pointer;color:#d4d9e0;font-size:.82rem;font-weight:600;transition:background .15s;"
                            onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                        📋 Copy Link
                    </button>
                    @auth
                    <a href="{{ route('posts.repost.form', $post) }}"
                       style="display:flex;align-items:center;gap:8px;width:100%;padding:8px 12px;background:transparent;border-radius:7px;color:#d4d9e0;font-size:.82rem;font-weight:600;transition:background .15s;"
                       onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                        ↺ Repost
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                       style="display:flex;align-items:center;gap:8px;width:100%;padding:8px 12px;background:transparent;border-radius:7px;color:#6b7280;font-size:.82rem;font-weight:600;transition:background .15s;"
                       onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                        Log in to Repost
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div style="padding:4px 0;">
        <!-- Sort + count header -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding:0 4px;">
            <span style="font-size:.88rem;font-weight:700;color:#f0f6fc;">
                💬 {{ $comments->count() }} Comment{{ $comments->count() !== 1 ? 's' : '' }}
            </span>
            <span style="font-size:.78rem;color:#6b7280;">Best · Top · New</span>
        </div>

        <!-- New comment form -->
        @auth
        <div style="background:#161b22;border:1px solid #21262d;border-radius:12px;padding:14px;margin-bottom:16px;">
            <form action="{{ route('comments.store', $post) }}" method="POST">
                @csrf
                <div style="display:flex;gap:10px;align-items:flex-start;">
                    <div class="pc-avatar" style="flex-shrink:0;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div style="flex:1;">
                        <textarea name="content" class="ts-input" rows="3"
                                  placeholder="What are your thoughts?" required
                                  style="margin-bottom:8px;font-size:.88rem;"></textarea>
                        <div style="display:flex;justify-content:flex-end;">
                            <button type="submit" class="btn-fill" style="font-size:.83rem;padding:7px 18px;">Comment</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @else
        <div style="background:#161b22;border:1px solid #21262d;border-radius:12px;padding:14px;margin-bottom:16px;text-align:center;font-size:.88rem;">
            <a href="{{ route('login') }}" class="text-link" style="font-weight:600;">Log in</a>
            <span style="color:#6b7280;"> to join the conversation</span>
        </div>
        @endauth

        <!-- Comment thread -->
        <div>
            @forelse($comments as $comment)
                @include('posts.partials.comment', ['comment' => $comment, 'post' => $post])
            @empty
                <div style="text-align:center;padding:40px;color:#6b7280;font-size:.88rem;">
                    No comments yet. Be the first!
                </div>
            @endforelse
        </div>
    </div>

</div>

<script>
function postView() {
    return {
        loading: false,
        summary: null,
        async summarize(url) {
            if (this.loading) return;
            this.loading = true;
            try {
                const r = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const d = await r.json();
                this.summary = d.summary;
            } catch(e) {
                this.summary = 'Failed to generate summary.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
</x-app-layout>
