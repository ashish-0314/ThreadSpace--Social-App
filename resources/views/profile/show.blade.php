<x-app-layout>
<div style="max-width:800px;margin:0 auto;padding:32px 16px;">

    {{-- Profile Header Card --}}
    <div style="background:#161b22;border:1px solid #30363d;border-radius:16px;overflow:hidden;margin-bottom:24px;">
        <div style="height:120px;background:linear-gradient(90deg, #1f6feb 0%, #0d1117 100%);"></div>
        <div style="padding:0 32px 32px;position:relative;">
            {{-- Avatar --}}
            <div style="position:absolute;top:-60px;left:32px;">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" style="width:120px;height:120px;border-radius:50%;border:4px solid #0d1117;object-fit:cover;background:#161b22;">
                @else
                    <div style="width:120px;height:120px;border-radius:50%;border:4px solid #0d1117;background:#21262d;display:flex;align-items:center;justify-content:center;font-size:3rem;font-weight:800;color:#8b949e;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Profile Actions --}}
            <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:16px;min-height:56px;">
                @auth
                    @if(!$isOwner)
                        @php
                            $isFollowing = auth()->user()->isFollowing($user->id);
                            $connStatus = auth()->user()->connectionStatus($user->id);
                        @endphp

                        {{-- Follow Button --}}
                        <form method="POST" action="{{ route('network.follow', $user) }}">
                            @csrf
                            <button type="submit" style="padding:8px 20px;border-radius:20px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .2s;
                                {{ $isFollowing ? 'background:transparent;border:1px solid #30363d;color:#f0f6fc;' : 'background:#2f81f7;border:1px solid #2f81f7;color:white;' }}">
                                {{ $isFollowing ? 'Following' : 'Follow' }}
                            </button>
                        </form>

                        {{-- Connect / Message Button --}}
                        @if($connStatus === 'accepted')
                            <a href="/messages/{{ $user->id }}" style="padding:8px 20px;border-radius:20px;background:#238636;color:white;font-weight:700;font-size:.88rem;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                                💬 Message
                            </a>
                        @elseif($connStatus === 'pending')
                             @php
                                $sentByMe = \App\Models\Connection::where('user_id', auth()->id())->where('connected_user_id', $user->id)->exists();
                             @endphp
                             @if($sentByMe)
                                <button disabled style="padding:8px 20px;border-radius:20px;background:#21262d;border:1px solid #30363d;color:#8b949e;font-weight:700;font-size:.88rem;cursor:default;">
                                    Pending
                                </button>
                             @else
                                <form method="POST" action="{{ route('network.accept', $user) }}">
                                    @csrf
                                    <button type="submit" style="padding:8px 20px;border-radius:20px;background:#238636;color:white;font-weight:700;font-size:.88rem;cursor:pointer;">
                                        Accept Request
                                    </button>
                                </form>
                             @endif
                        @else
                            <form method="POST" action="{{ route('network.connect', $user) }}">
                                @csrf
                                <button type="submit" style="padding:8px 20px;border-radius:20px;border:1px solid #2f81f7;color:#2f81f7;background:transparent;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .2s;"
                                        onmouseover="this.style.background='rgba(47,129,247,.1)'" onmouseout="this.style.background='transparent'">
                                    Connect
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="?tab=settings" style="padding:8px 20px;border-radius:20px;background:#21262d;border:1px solid #30363d;color:#d4d9e0;font-weight:700;font-size:.88rem;text-decoration:none;">
                            Edit Profile
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" style="padding:8px 20px;border-radius:20px;background:#2f81f7;color:white;font-weight:700;font-size:.88rem;text-decoration:none;">
                        Follow
                    </a>
                @endauth
            </div>

            {{-- User Info --}}
            <div style="margin-top:20px;">
                <h1 style="font-size:1.5rem;font-weight:800;color:#f0f6fc;margin:0 0 4px;">{{ $user->name }}</h1>
                <p style="font-size:1rem;color:#8b949e;margin:0 0 16px;">u/{{ $user->name }}</p>
                
                @if($user->bio)
                    <p style="font-size:.92rem;color:#d4d9e0;line-height:1.6;max-width:600px;margin-bottom:20px;">{{ $user->bio }}</p>
                @endif

                <div style="display:flex;gap:20px;font-size:.88rem;color:#8b949e;">
                    <span><strong style="color:#f0f6fc;">{{ $user->followers()->count() }}</strong> followers</span>
                    <span><strong style="color:#f0f6fc;">{{ $user->following()->count() }}</strong> following</span>
                    @php
                        $connCount = \App\Models\Connection::where(function($q) use ($user) {
                            $q->where('user_id', $user->id)->orWhere('connected_user_id', $user->id);
                        })->where('status', 'accepted')->count();
                    @endphp
                    <span><strong style="color:#f0f6fc;">{{ $connCount }}</strong> connections</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Unified Tab Navigation --}}
    <div style="display:flex;border-bottom:1px solid #30363d;margin-bottom:24px;gap:24px;overflow-x:auto;">
        <a href="?tab=posts" style="white-space:nowrap;padding:12px 4px;font-size:.9rem;font-weight:600;text-decoration:none;transition:all .2s;border-bottom:2px solid {{ $tab === 'posts' ? '#2f81f7' : 'transparent' }};color:{{ $tab === 'posts' ? '#f0f6fc' : '#8b949e' }};">
            📝 Posts
        </a>
        <a href="?tab=comments" style="white-space:nowrap;padding:12px 4px;font-size:.9rem;font-weight:600;text-decoration:none;transition:all .2s;border-bottom:2px solid {{ $tab === 'comments' ? '#2f81f7' : 'transparent' }};color:{{ $tab === 'comments' ? '#f0f6fc' : '#8b949e' }};">
            💬 Comments
        </a>
        
        @if($isOwner)
            <a href="?tab=upvoted" style="white-space:nowrap;padding:12px 4px;font-size:.9rem;font-weight:600;text-decoration:none;transition:all .2s;border-bottom:2px solid {{ $tab === 'upvoted' ? '#2f81f7' : 'transparent' }};color:{{ $tab === 'upvoted' ? '#f0f6fc' : '#8b949e' }};">
                🔼 Upvoted
            </a>
            <a href="?tab=downvoted" style="white-space:nowrap;padding:12px 4px;font-size:.9rem;font-weight:600;text-decoration:none;transition:all .2s;border-bottom:2px solid {{ $tab === 'downvoted' ? '#2f81f7' : 'transparent' }};color:{{ $tab === 'downvoted' ? '#f0f6fc' : '#8b949e' }};">
                🔽 Downvoted
            </a>
            <a href="?tab=settings" style="white-space:nowrap;padding:12px 4px;font-size:.9rem;font-weight:600;text-decoration:none;transition:all .2s;border-bottom:2px solid {{ $tab === 'settings' ? '#2f81f7' : 'transparent' }};color:{{ $tab === 'settings' ? '#f0f6fc' : '#8b949e' }};">
                ⚙️ Settings
            </a>
        @endif
    </div>

    {{-- Content Area --}}
    <div>
        @if($tab === 'settings' && $isOwner)
            {{-- Settings Forms --}}
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
        @else
            {{-- Activity Feeds (Posts, Comments, Votes) --}}
            @if($items->isEmpty())
                <div style="padding:60px 20px;text-align:center;background:#161b22;border:1px solid #30363d;border-radius:16px;">
                    <div style="font-size:3rem;margin-bottom:16px;opacity:0.5;">🔍</div>
                    <p style="color:#f0f6fc;font-weight:700;font-size:1.1rem;margin-bottom:8px;">Nothing to show here</p>
                    <p style="color:#8b949e;font-size:.9rem;">
                        @if($tab === 'posts') This user hasn't created any posts yet.
                        @elseif($tab === 'comments') This user hasn't commented on anything yet.
                        @else No activity found for this tab.
                        @endif
                    </p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach($items as $post)
                        <div style="background:#161b22;border:1px solid #30363d;border-radius:16px;padding:20px;position:relative;transition:all .2s;"
                             onmouseover="this.style.borderColor='#484f58';this.style.transform='translateY(-2px)'" 
                             onmouseout="this.style.borderColor='#30363d';this.style.transform='none'">
                            
                            {{-- Metadata --}}
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                                @if($post->user && $tab !== 'posts')
                                    <span style="font-size:.8rem;color:#8b949e;">by <strong style="color:#c9d1d9;">u/{{ $post->user->name }}</strong></span>
                                    <span style="color:#484f58;">•</span>
                                @endif
                                @if($post->community)
                                    <span style="font-size:.8rem;color:#2f81f7;font-weight:600;">c/{{ $post->community->name }}</span>
                                    <span style="color:#484f58;">•</span>
                                @endif
                                <span style="font-size:.8rem;color:#8b949e;">{{ $post->created_at->diffForHumans() }}</span>
                                
                                <span style="margin-left:auto;font-size:.7rem;font-weight:800;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em;
                                    background: {{ ['Question'=>'rgba(139,92,246,.15)','Discussion'=>'rgba(59,130,246,.15)','Tutorial'=>'rgba(16,185,129,.15)','Opinion'=>'rgba(245,158,11,.15)'][$post->intent] ?? 'rgba(100,100,100,.15)' }};
                                    color: {{ ['Question'=>'#a78bfa','Discussion'=>'#60a5fa','Tutorial'=>'#34d399','Opinion'=>'#fbbf24'][$post->intent] ?? '#8b949e' }};">
                                    {{ $post->intent }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <a href="{{ route('posts.show', $post) }}" style="display:block;font-size:1.1rem;font-weight:700;color:#f0f6fc;text-decoration:none;line-height:1.4;margin-bottom:12px;"
                               onmouseover="this.style.color='#2f81f7'" onmouseout="this.style.color='#f0f6fc'">
                                {{ $post->title }}
                            </a>

                            {{-- Actions/Stats --}}
                            <div style="display:flex;align-items:center;gap:20px;font-size:.85rem;color:#8b949e;">
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                                    {{ (int)($post->upvotes ?? 0) - (int)($post->downvotes ?? 0) }}
                                </div>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                    {{ $post->comments()->count() }}
                                </div>
                                
                                <div style="margin-left:auto;display:flex;gap:8px;">
                                    <a href="{{ route('posts.show', $post) }}" style="padding:6px 14px;border-radius:8px;background:#21262d;border:1px solid #30363d;color:#d4d9e0;font-size:.78rem;font-weight:700;text-decoration:none;transition:background .2s;"
                                       onmouseover="this.style.background='#30363d'" onmouseout="this.style.background='#21262d'">
                                        View Full Post
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div style="margin-top:32px;">
                    {{ $items->appends(['tab' => $tab])->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
</x-app-layout>
