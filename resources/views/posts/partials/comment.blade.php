{{-- Threaded Comment Partial --}}
{{-- Props: $comment, $post --}}
<div x-data="{ collapsed: false, replyOpen: false }" style="margin-bottom:0;">

    <div style="display:flex;gap:0;align-items:flex-start;">

        {{-- ── Left gutter: collapse line ─────────────────── --}}
        <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0;width:32px;padding-top:2px;">
            {{-- Collapse toggle button --}}
            <button @click="collapsed=!collapsed"
                    style="background:transparent;border:1px solid #30363d;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#6b7280;flex-shrink:0;font-size:.65rem;line-height:1;transition:border-color .15s,color .15s;"
                    onmouseover="this.style.borderColor='#58a6ff';this.style.color='#58a6ff'"
                    onmouseout="this.style.borderColor='#30363d';this.style.color='#6b7280'"
                    :title="collapsed ? 'Expand thread' : 'Collapse thread'">
                <span x-show="!collapsed">−</span>
                <span x-show="collapsed">+</span>
            </button>
            {{-- Thread line (only if has replies) --}}
            @if($comment->replies && $comment->replies->count() > 0)
            <div x-show="!collapsed"
                 style="width:2px;flex:1;background:#21262d;margin-top:6px;min-height:20px;border-radius:1px;cursor:pointer;"
                 @click="collapsed=true"
                 onmouseover="this.style.background='#58a6ff'"
                 onmouseout="this.style.background='#21262d'"></div>
            @endif
        </div>

        {{-- ── Comment body ─────────────────────────────── --}}
        <div style="flex:1;min-width:0;padding-left:8px;padding-bottom:12px;">

            {{-- Header: avatar + name + time --}}
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:6px;">
                <div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;color:#fff;flex-shrink:0;">
                    {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                </div>
                <span style="font-size:.83rem;font-weight:700;color:#d4d9e0;">{{ $comment->user->name ?? 'Unknown' }}</span>
                <span style="color:#374151;font-size:.75rem;">•</span>
                <span style="font-size:.76rem;color:#6b7280;">{{ $comment->created_at->diffForHumans() }}</span>
                @if($comment->is_best_answer)
                    <span style="font-size:.7rem;font-weight:700;color:#4ade80;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);padding:1px 8px;border-radius:999px;">
                        ✓ Best Answer
                    </span>
                @endif
            </div>

            {{-- Collapsed summary --}}
            <div x-show="collapsed" style="font-size:.82rem;color:#4b5563;font-style:italic;margin-bottom:6px;cursor:pointer;" @click="collapsed=false">
                [Thread collapsed — click to expand]
            </div>

            {{-- Full content --}}
            <div x-show="!collapsed" x-transition>
                {{-- Comment text --}}
                <div style="font-size:.9rem;color:#d4d9e0;line-height:1.75;margin-bottom:10px;white-space:pre-wrap;">{{ $comment->content }}</div>

                {{-- ── Action bar ────────────────────────── --}}
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">

                    {{-- Vote: ↑ count ↓ --}}
                    @auth
                    <form action="{{ route('vote') }}" method="POST" style="display:inline-flex;align-items:center;">
                        @csrf
                        <input type="hidden" name="votable_id"   value="{{ $comment->id }}">
                        <input type="hidden" name="votable_type" value="Comment">
                        <input type="hidden" name="value" id="cv-{{ $comment->id }}" value="1">
                        <div class="vote-pill">
                            <button type="submit" class="vote-btn-up" title="Upvote"
                                    onclick="document.getElementById('cv-{{ $comment->id }}').value='1'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                </svg>
                            </button>
                            <span class="vote-score">{{ (int)($comment->upvotes ?? 0) - (int)($comment->downvotes ?? 0) }}</span>
                            <div class="vote-divider"></div>
                            <button type="submit" class="vote-btn-down" title="Downvote"
                                    onclick="document.getElementById('cv-{{ $comment->id }}').value='-1'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="vote-pill" style="text-decoration:none;">
                        <span class="vote-btn-up" style="cursor:pointer;"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg></span>
                        <span class="vote-score">{{ (int)($comment->upvotes ?? 0) - (int)($comment->downvotes ?? 0) }}</span>
                        <div class="vote-divider"></div>
                        <span class="vote-btn-down" style="cursor:pointer;"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
                    </a>
                    @endauth

                    {{-- Reply --}}
                    @auth
                    <button @click="replyOpen=!replyOpen" class="act-pill" style="height:30px;padding:0 12px;font-size:.78rem;background:transparent;border:none;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Reply
                    </button>
                    @endauth

                    {{-- Share --}}
                    <button class="act-pill" style="height:30px;padding:0 12px;font-size:.78rem;background:transparent;border:none;"
                            onclick="navigator.clipboard.writeText('{{ url()->current() }}#comment-{{ $comment->id }}').then(()=>{this.textContent='✓ Copied';setTimeout(()=>this.innerHTML='&#x2197; Share',1400)})">
                        ↗ Share
                    </button>

                    {{-- Mark Best Answer --}}
                    @auth
                    @if(auth()->id() === $post->user_id && $post->intent === 'Question' && !$comment->is_best_answer)
                    <form action="{{ route('comments.best_answer', $comment) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="act-pill" style="height:30px;padding:0 12px;font-size:.78rem;background:transparent;border:none;color:#4ade80;">
                            ✓ Mark Best
                        </button>
                    </form>
                    @endif
                    @endauth
                </div>

                {{-- Reply box --}}
                <div x-show="replyOpen" x-transition style="margin-top:12px;">
                    @auth
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="content" class="ts-input" rows="2"
                                  placeholder="Write a reply…" required
                                  style="margin-bottom:8px;font-size:.87rem;"></textarea>
                        <div style="display:flex;justify-content:flex-end;gap:8px;">
                            <button type="button" @click="replyOpen=false" class="btn-outline" style="padding:5px 12px;font-size:.78rem;">Cancel</button>
                            <button type="submit" class="btn-fill" style="padding:5px 12px;font-size:.78rem;">Reply</button>
                        </div>
                    </form>
                    @endauth
                </div>

                {{-- Nested replies --}}
                @if($comment->replies && $comment->replies->count() > 0)
                <div style="margin-top:4px;">
                    @foreach($comment->replies as $reply)
                        @include('posts.partials.comment', ['comment' => $reply, 'post' => $post])
                    @endforeach
                </div>
                @endif

            </div>{{-- /x-show !collapsed --}}
        </div>{{-- /comment body --}}
    </div>
</div>
