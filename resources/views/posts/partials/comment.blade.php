{{-- Threaded Comment Partial --}}
{{-- Props: $comment, $post --}}
<div class="comment-thread-container" x-data="{ collapsed: false, replyOpen: false }">
    <div style="display:flex; align-items:stretch;">
        
        <!-- Left Column: Avatar & Vertical Line -->
        <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0; width:24px; position:relative;">
            
            <!-- Branch for nested comments -->
            @if(($comment->depth ?? 0) > 0)
                <div class="thread-branch"></div>
                <!-- Collapse Button -->
                <button @click="collapsed=!collapsed" class="comment-collapse-btn">
                    <span x-show="!collapsed" style="line-height:1;margin-top:-1px;">−</span>
                    <span x-show="collapsed" style="line-height:1;margin-top:-1px;">+</span>
                </button>
            @endif

            <!-- Avatar -->
            <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;color:#fff;flex-shrink:0;z-index:2;position:relative;">
                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
            </div>

            <!-- Vertical Line (Visible if this comment has replies and is not collapsed) -->
            @if($comment->replies && $comment->replies->count() > 0)
                <div x-show="!collapsed" class="thread-vertical-line"></div>
            @endif
        </div>

        <!-- Right Column: Content & Replies -->
        <div style="flex:1; min-width:0; padding-left:8px; padding-bottom:8px;">
            <!-- Header: name + time -->
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;min-height:24px;">
                <span style="font-size:.85rem;font-weight:700;color:#f0f6fc;">{{ $comment->user->name ?? 'Unknown' }}</span>
                <span style="font-size:.76rem;color:#6b7280;">• {{ $comment->created_at->diffForHumans() }}</span>
                @if($comment->is_best_answer)
                    <span style="font-size:.65rem;font-weight:800;color:#4ade80;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);padding:1px 8px;border-radius:999px;margin-left:4px;">
                        ✓ BEST
                    </span>
                @endif
            </div>

            {{-- Collapsed summary --}}
            <div x-show="collapsed" style="font-size:.8rem;color:#6b7280;font-style:italic;margin-bottom:12px;cursor:pointer;" @click="collapsed=false">
                Thread collapsed
            </div>

            {{-- Full content --}}
            <div x-show="!collapsed" x-transition>
                <div style="font-size:.92rem;color:#d4d9e0;line-height:1.6;margin-bottom:12px;white-space:pre-wrap;">{{ $comment->content }}</div>

                {{-- Action bar --}}
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                    {{-- Vote --}}
                    @auth
                    <form action="{{ route('vote') }}" method="POST" style="display:inline-flex;">
                        @csrf
                        <input type="hidden" name="votable_id"   value="{{ $comment->id }}">
                        <input type="hidden" name="votable_type" value="Comment">
                        <input type="hidden" name="value" id="cv-{{ $comment->id }}" value="1">
                        <div style="display:flex;align-items:center;gap:4px;background:#21262d;padding:2px 8px;border-radius:12px;border:1px solid #30363d;">
                            <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;color:#8b949e;" onmouseover="this.style.color='#22c55e'" onmouseout="this.style.color='#8b949e'" onclick="document.getElementById('cv-{{ $comment->id }}').value='1'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <span style="font-size:.78rem;font-weight:700;color:#d4d9e0;min-width:14px;text-align:center;">{{ (int)($comment->upvotes ?? 0) - (int)($comment->downvotes ?? 0) }}</span>
                            <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;color:#8b949e;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#8b949e'" onclick="document.getElementById('cv-{{ $comment->id }}').value='-1'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    </form>
                    @endauth

                    <button @click="replyOpen=!replyOpen" style="background:none;border:none;padding:0;color:#8b949e;font-size:.78rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:4px;" onmouseover="this.style.color='#f0f6fc'" onmouseout="this.style.color='#8b949e'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Reply
                    </button>
                    
                    <button style="background:none;border:none;padding:0;color:#8b949e;font-size:.78rem;font-weight:700;cursor:pointer;" onmouseover="this.style.color='#f0f6fc'" onmouseout="this.style.color='#8b949e'" onclick="navigator.clipboard.writeText('{{ url()->current() }}#comment-{{ $comment->id }}')">
                        Share
                    </button>
                </div>

                {{-- Reply box --}}
                <div x-show="replyOpen" x-transition style="margin-bottom:16px;">
                    @auth
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="content" class="ts-input" rows="2" placeholder="What are your thoughts?" required style="margin-bottom:8px;font-size:.88rem;"></textarea>
                        <div style="display:flex;gap:8px;">
                            <button type="submit" class="btn-fill" style="padding:5px 14px;font-size:.8rem;">Post Reply</button>
                            <button type="button" @click="replyOpen=false" style="background:none;border:none;color:#8b949e;font-size:.8rem;cursor:pointer;">Cancel</button>
                        </div>
                    </form>
                    @endauth
                </div>

                {{-- Nested replies --}}
                @if($comment->replies && $comment->replies->count() > 0)
                    <div class="comment-replies">
                        @foreach($comment->replies as $reply)
                            @include('posts.partials.comment', ['comment' => $reply, 'post' => $post])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
