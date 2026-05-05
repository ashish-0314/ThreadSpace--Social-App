{{-- Post Card Partial  – home feed & community pages --}}
<div class="post-card" x-data="{ shareOpen: false }">

    {{-- ── Repost header banner ─────────────────────────── --}}
    @if($post->is_repost)
    <div style="display:flex;align-items:center;gap:7px;margin-bottom:10px;padding-bottom:10px;border-bottom:1px solid #21262d;">
        @if($post->user && $post->user->avatar_url)
            <img src="{{ $post->user->avatar_url }}" class="pc-avatar" style="width:22px;height:22px;object-fit:cover;border:none;">
        @else
            <div class="pc-avatar" style="width:22px;height:22px;font-size:.58rem;">{{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}</div>
        @endif
        <span style="font-size:.78rem;color:#8b949e;">
            <span style="font-weight:600;color:#d4d9e0;">u/{{ $post->user->name ?? 'Unknown' }}</span>
            &nbsp;↺ reposted
            <span style="color:#6b7280;">•</span>
            {{ $post->created_at->diffForHumans() }}
        </span>
        @if($post->community)
            <a href="{{ route('communities.show', $post->community->slug) }}" class="pc-comm" style="margin-left:auto;font-size:.75rem;">c/{{ $post->community->name }}</a>
        @endif
    </div>
    @endif

    {{-- ── Normal post header (non-repost) ────────────── --}}
    @if(!$post->is_repost)
    <div class="pc-header">
        @if($post->user && $post->user->avatar_url)
            <img src="{{ $post->user->avatar_url }}" class="pc-avatar" style="object-fit:cover;border:none;">
        @else
            <div class="pc-avatar">{{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}</div>
        @endif
        <span class="pc-author">u/{{ $post->user->name ?? 'Unknown' }}</span>
        <span class="pc-dot">•</span>
        <span class="pc-time">{{ $post->created_at->diffForHumans() }}</span>
        @if($post->community)
            <span class="pc-dot">·</span>
            <a href="{{ route('communities.show', $post->community->slug) }}" class="pc-comm">c/{{ $post->community->name }}</a>
        @endif
        <button class="pc-menu">···</button>
    </div>
    @endif

    {{-- ── Repost user comment ─────────────────────────── --}}
    @if($post->is_repost && $post->repost_comment)
    <div style="font-size:.9rem;color:#d4d9e0;line-height:1.65;margin-bottom:12px;">{{ $post->repost_comment }}</div>
    @endif

    {{-- ── For reposts: show embedded original post ────── --}}
    @if($post->is_repost && $post->originalPost)
        @php $orig = $post->originalPost; @endphp
        <a href="{{ route('posts.show', $orig) }}" style="display:block;text-decoration:none;margin-bottom:12px;">
            <div style="background:#0d1117;border:1px solid #30363d;border-radius:10px;padding:12px 14px;transition:border-color .2s;" onmouseover="this.style.borderColor='#58a6ff55'" onmouseout="this.style.borderColor='#30363d'">
                <!-- Original author -->
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                    @if($orig->user && $orig->user->avatar_url)
                        <img src="{{ $orig->user->avatar_url }}" style="width:20px;height:20px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    @else
                        <div style="width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#db2777);display:flex;align-items:center;justify-content:center;font-size:.55rem;font-weight:800;color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($orig->user->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    <span style="font-size:.76rem;font-weight:600;color:#8b949e;">u/{{ $orig->user->name ?? 'Unknown' }}</span>
                    <span style="color:#374151;font-size:.72rem;">•</span>
                    <span style="font-size:.74rem;color:#6b7280;">{{ $orig->created_at->diffForHumans() }}</span>
                    @if($orig->community)
                        <span style="color:#374151;font-size:.72rem;">·</span>
                        <span style="font-size:.74rem;color:#58a6ff;font-weight:600;">c/{{ $orig->community->name }}</span>
                    @endif
                </div>
                <!-- Original title -->
                <div style="font-size:.9rem;font-weight:700;color:#f0f6fc;margin-bottom:6px;line-height:1.35;">{{ $orig->title }}</div>
                <!-- Original content preview -->
                @if($orig->type === 'text' && $orig->content)
                    <div style="font-size:.82rem;color:#6b7280;line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $orig->content }}</div>
                @elseif(in_array($orig->type, ['image', 'media']) && ($orig->image_url || !empty($orig->media)))
                    @php $mediaList = !empty($orig->media) ? $orig->media : ($orig->image_url ? [['url' => $orig->image_url, 'type' => 'image']] : []); @endphp
                    <div class="media-carousel" style="max-height:160px;margin-bottom:12px;">
                        @foreach($mediaList as $media)
                            @if($media['type'] === 'image')
                                <img src="{{ $media['url'] }}" class="media-item" style="max-height:160px;">
                            @elseif($media['type'] === 'video')
                                <video src="{{ $media['url'] }}" controls class="media-item" style="max-height:160px;"></video>
                            @elseif($media['type'] === 'audio')
                                <audio src="{{ $media['url'] }}" controls class="media-item" style="height:40px;"></audio>
                            @endif
                        @endforeach
                    </div>
                @elseif($orig->type === 'link' && $orig->content)
                    <span style="font-size:.8rem;color:#58a6ff;">🔗 {{ $orig->content }}</span>
                @endif
                <div style="margin-top:8px;">
                    <span class="pc-flair pc-flair-{{ $orig->intent }}">{{ $orig->intent }}</span>
                </div>
            </div>
        </a>
    @else
        {{-- ── Normal post: title + flair + content ─────── --}}
        <a href="{{ route('posts.show', $post) }}" class="pc-title">{{ $post->title }}</a>
        <div style="margin-bottom:10px;">
            <span class="pc-flair pc-flair-{{ $post->intent }}">{{ $post->intent }}</span>
        </div>
        @if(in_array($post->type, ['image', 'media']) && ($post->image_url || !empty($post->media)))
            @php $mediaList = !empty($post->media) ? $post->media : ($post->image_url ? [['url' => $post->image_url, 'type' => 'image']] : []); @endphp
            <div class="media-carousel">
                @foreach($mediaList as $media)
                    @if($media['type'] === 'image')
                        <img src="{{ $media['url'] }}" class="media-item" alt="">
                    @elseif($media['type'] === 'video')
                        <video src="{{ $media['url'] }}" controls class="media-item"></video>
                    @elseif($media['type'] === 'audio')
                        <audio src="{{ $media['url'] }}" controls class="media-item" style="height:40px;"></audio>
                    @endif
                @endforeach
            </div>
        @elseif($post->type === 'text' && $post->content)
            <div class="pc-body">{{ $post->content }}</div>
        @elseif($post->type === 'link' && $post->content)
            <div class="pc-body"><a href="{{ $post->content }}" target="_blank" class="text-link">🔗 {{ $post->content }}</a></div>
        @endif
    @endif

    {{-- ── Action bar ──────────────────────────────────── --}}
    <div class="pc-actions">

        {{-- Vote pill – Alpine reactive, instant color on click, no page reload --}}
        @auth
        @php $myVote = $userVotes[(string)$post->id] ?? null; @endphp
        <div x-data="voteWidget(
                '{{ $post->id }}',
                'Post',
                {{ $myVote ?? 'null' }},
                {{ (int)($post->upvotes ?? 0) - (int)($post->downvotes ?? 0) }}
             )"
             class="vote-pill"
             :class="pillClass">
            <button type="button" class="vote-btn-up"
                    :title="userVote === 1 ? 'Remove upvote' : 'Upvote'"
                    @click="castVote(1)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 4L3 15h6v5h6v-5h6L12 4z"/>
                </svg>
            </button>
            <span class="vote-score" x-text="score"></span>
            <div class="vote-divider"></div>
            <button type="button" class="vote-btn-down"
                    :title="userVote === -1 ? 'Remove downvote' : 'Downvote'"
                    @click="castVote(-1)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 20l9-11h-6V4H9v5H3l9 11z"/>
                </svg>
            </button>
        </div>
        @else
        <a href="{{ route('login') }}" class="vote-pill" style="text-decoration:none;" title="Log in to vote">
            <span class="vote-btn-up"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4L3 15h6v5h6v-5h6L12 4z"/></svg></span>
            <span class="vote-score">{{ (int)($post->upvotes ?? 0) - (int)($post->downvotes ?? 0) }}</span>
            <div class="vote-divider"></div>
            <span class="vote-btn-down"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 20l9-11h-6V4H9v5H3l9 11z"/></svg></span>
        </a>
        @endauth

        {{-- Comments --}}
        <a href="{{ route('posts.show', $post) }}" class="act-pill">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Comments
        </a>

        {{-- Share dropdown --}}
        <div style="position:relative;" @click.outside="shareOpen=false">
            <button class="act-pill" @click="shareOpen=!shareOpen" style="border:none;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                Share
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>

            {{-- Dropdown menu --}}
            <div x-show="shareOpen" x-transition
                 style="position:absolute;bottom:calc(100% + 6px);left:0;background:#161b22;border:1px solid #30363d;border-radius:10px;padding:4px;min-width:180px;z-index:50;box-shadow:0 8px 24px rgba(0,0,0,.5);">

                {{-- Copy Link --}}
                <button
                    onclick="navigator.clipboard.writeText('{{ route('posts.show', $post) }}').then(()=>{document.getElementById('copied-{{ $post->id }}').style.display='block';setTimeout(()=>document.getElementById('copied-{{ $post->id }}').style.display='none',1800)})"
                    style="display:flex;align-items:center;gap:8px;width:100%;padding:8px 12px;background:transparent;border:none;border-radius:7px;cursor:pointer;color:#d4d9e0;font-size:.82rem;font-weight:600;text-align:left;transition:background .15s;"
                    onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Copy Link
                </button>
                <span id="copied-{{ $post->id }}" style="display:none;font-size:.75rem;color:#4ade80;padding:0 12px 6px;display:none;">✓ Link copied!</span>

                {{-- Repost --}}
                @auth
                <a href="{{ route('posts.repost.form', $post) }}"
                   style="display:flex;align-items:center;gap:8px;width:100%;padding:8px 12px;background:transparent;border:none;border-radius:7px;cursor:pointer;color:#d4d9e0;font-size:.82rem;font-weight:600;transition:background .15s;"
                   onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    ↺ Repost
                </a>
                @else
                <a href="{{ route('login') }}"
                   style="display:flex;align-items:center;gap:8px;width:100%;padding:8px 12px;background:transparent;border:none;border-radius:7px;cursor:pointer;color:#6b7280;font-size:.82rem;font-weight:600;transition:background .15s;"
                   onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Log in to Repost
                </a>
                @endauth
            </div>
        </div>

    </div>
</div>
