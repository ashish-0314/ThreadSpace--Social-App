<x-app-layout>
<div style="max-width:1200px;margin:0 auto;padding:40px 20px;">

    {{-- Premium Profile Header Card --}}
    <div style="background:#161b22;border:1px solid #30363d;border-radius:24px;overflow:hidden;margin-bottom:32px;box-shadow:0 20px 40px rgba(0,0,0,0.4);position:relative;">
        
        {{-- Animated Gradient Banner --}}
        <div style="height:160px;background:linear-gradient(135deg, #1e3a8a 0%, #4c1d95 50%, #0f172a 100%);background-size:200% 200%;animation:gradientBG 10s ease infinite;position:relative;">
            <div style="position:absolute;inset:0;background:url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')"></div>
        </div>

        <div style="padding:0 40px 40px;position:relative;">
            
            {{-- Avatar & Quick Actions Layout --}}
            <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-top:-64px;margin-bottom:24px;">
                {{-- Avatar --}}
                <div style="position:relative;z-index:10;">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" style="width:140px;height:140px;border-radius:50%;border:6px solid #161b22;object-fit:cover;background:#161b22;box-shadow:0 8px 24px rgba(0,0,0,0.5);">
                    @else
                        <div style="width:140px;height:140px;border-radius:50%;border:6px solid #161b22;background:linear-gradient(135deg,#3b82f6,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:3.5rem;font-weight:800;color:#fff;box-shadow:0 8px 24px rgba(0,0,0,0.5);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Profile Actions --}}
                <div style="display:flex;gap:12px;padding-bottom:8px;">
                    @auth
                        @if(Auth::user()->isAdmin() && !$isOwner)
                            {{-- Admin observing, no actions needed --}}
                        @elseif(!$isOwner)
                            @php
                                $isFollowing = auth()->user()->isFollowing($user->id);
                                $connStatus = auth()->user()->connectionStatus($user->id);
                            @endphp

                            {{-- Follow Button --}}
                            <form method="POST" action="{{ route('network.follow', $user) }}">
                                @csrf
                                <button type="submit" style="padding:10px 24px;border-radius:30px;font-weight:700;font-size:.9rem;cursor:pointer;transition:all .2s;
                                    {{ $isFollowing ? 'background:rgba(255,255,255,0.05);border:1px solid #30363d;color:#f0f6fc;' : 'background:linear-gradient(135deg,#2f81f7,#1f6feb);border:none;color:white;box-shadow:0 4px 12px rgba(47,129,247,0.3);' }}"
                                    onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            </form>

                            {{-- Connect / Message Button --}}
                            @if($connStatus === 'accepted')
                                <a href="/messages/{{ $user->id }}" style="padding:10px 24px;border-radius:30px;background:rgba(35,134,54,0.1);border:1px solid #238636;color:#2ea043;font-weight:700;font-size:.9rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all 0.2s;"
                                   onmouseover="this.style.background='rgba(35,134,54,0.2)';this.style.transform='translateY(-1px)'" onmouseout="this.style.background='rgba(35,134,54,0.1)';this.style.transform='none'">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Message
                                </a>
                            @elseif($connStatus === 'pending')
                                 @php
                                    $sentByMe = \App\Models\Connection::where('user_id', auth()->id())->where('connected_user_id', $user->id)->exists();
                                 @endphp
                                 @if($sentByMe)
                                    <button disabled style="padding:10px 24px;border-radius:30px;background:#21262d;border:1px solid #30363d;color:#8b949e;font-weight:700;font-size:.9rem;cursor:default;">
                                        Pending
                                    </button>
                                 @else
                                    <form method="POST" action="{{ route('network.accept', $user) }}">
                                        @csrf
                                        <button type="submit" style="padding:10px 24px;border-radius:30px;background:#238636;border:none;color:white;font-weight:700;font-size:.9rem;cursor:pointer;transition:all 0.2s;"
                                                onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
                                            Accept Request
                                        </button>
                                    </form>
                                 @endif
                            @else
                                <form method="POST" action="{{ route('network.connect', $user) }}">
                                    @csrf
                                    <button type="submit" style="padding:10px 24px;border-radius:30px;border:1px solid #2f81f7;color:#58a6ff;background:transparent;font-weight:700;font-size:.9rem;cursor:pointer;transition:all .2s;"
                                            onmouseover="this.style.background='rgba(47,129,247,.1)';this.style.transform='translateY(-1px)'" onmouseout="this.style.background='transparent';this.style.transform='none'">
                                        Connect
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="?tab=settings" style="padding:10px 24px;border-radius:30px;background:rgba(255,255,255,0.05);border:1px solid #30363d;color:#c9d1d9;font-weight:700;font-size:.9rem;text-decoration:none;transition:all 0.2s;display:inline-flex;align-items:center;gap:6px;"
                               onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Edit Profile
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" style="padding:10px 24px;border-radius:30px;background:linear-gradient(135deg,#2f81f7,#1f6feb);border:none;color:white;font-weight:700;font-size:.9rem;text-decoration:none;">
                            Follow
                        </a>
                    @endauth
                </div>
            </div>

            {{-- User Info --}}
            <div>
                <h1 style="font-size:1.8rem;font-weight:800;color:#f0f6fc;margin:0 0 4px;letter-spacing:-0.5px;">{{ $user->name }}</h1>
                <p style="font-size:1.05rem;color:#8b949e;margin:0 0 20px;">u/{{ $user->name }}</p>
                
                @if($user->bio)
                    <p style="font-size:.95rem;color:#c9d1d9;line-height:1.6;max-width:700px;margin-bottom:24px;">{{ $user->bio }}</p>
                @endif

                {{-- Premium Stats Row --}}
                <div style="display:flex;gap:32px;font-size:.9rem;color:#8b949e;background:rgba(255,255,255,0.02);padding:16px 24px;border-radius:16px;border:1px solid rgba(255,255,255,0.05);width:fit-content;">
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <strong style="color:#f0f6fc;font-size:1.2rem;">{{ $user->followers()->count() }}</strong>
                        <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Followers</span>
                    </div>
                    <div style="width:1px;background:#30363d;"></div>
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <strong style="color:#f0f6fc;font-size:1.2rem;">{{ $user->following()->count() }}</strong>
                        <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Following</span>
                    </div>
                    <div style="width:1px;background:#30363d;"></div>
                    @php
                        $connCount = \App\Models\Connection::where(function($q) use ($user) {
                            $q->where('user_id', $user->id)->orWhere('connected_user_id', $user->id);
                        })->where('status', 'accepted')->count();
                    @endphp
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <strong style="color:#f0f6fc;font-size:1.2rem;">{{ $connCount }}</strong>
                        <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">Connections</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pill Navigation --}}
    <div style="display:flex;margin-bottom:32px;gap:12px;overflow-x:auto;padding-bottom:8px;scrollbar-width:none;">
        <style>
            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .nav-pill {
                white-space:nowrap;
                padding:10px 20px;
                border-radius:30px;
                font-size:.9rem;
                font-weight:600;
                text-decoration:none;
                transition:all .2s ease;
                background:rgba(255,255,255,0.03);
                border:1px solid #30363d;
                color:#8b949e;
                display:inline-flex;
                align-items:center;
                gap:8px;
            }
            .nav-pill:hover {
                background:rgba(255,255,255,0.08);
                color:#c9d1d9;
            }
            .nav-pill.active {
                background:#f0f6fc;
                border-color:#f0f6fc;
                color:#0d1117;
            }
            .nav-pill svg {
                width: 16px;
                height: 16px;
            }
        </style>
        <a href="?tab=posts" class="nav-pill {{ $tab === 'posts' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Posts
        </a>
        <a href="?tab=comments" class="nav-pill {{ $tab === 'comments' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg> Comments
        </a>
        
        @if($isOwner)
            <a href="?tab=upvoted" class="nav-pill {{ $tab === 'upvoted' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg> Upvoted
            </a>
            <a href="?tab=downvoted" class="nav-pill {{ $tab === 'downvoted' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg> Downvoted
            </a>
            <a href="?tab=settings" class="nav-pill {{ $tab === 'settings' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Settings
            </a>
        @endif
    </div>

    {{-- Content Area --}}
    <div>
        @if($tab === 'settings' && $isOwner)
            {{-- Settings Forms --}}
            <div style="display:flex;flex-wrap:wrap;gap:24px;align-items:flex-start;">
                {{-- Left Column: Main Profile Info --}}
                <div style="flex:1 1 650px;background:#161b22;border:1px solid #30363d;border-radius:24px;padding:40px;box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Right Column: Security & Deletion --}}
                <div style="flex:1 1 350px;display:flex;flex-direction:column;gap:24px;">
                    <div style="background:#161b22;border:1px solid #30363d;border-radius:24px;padding:32px;box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div style="background:rgba(239,68,68,0.05);border:1px solid rgba(239,68,68,0.2);border-radius:24px;padding:32px;box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        @else
            {{-- Activity Feeds --}}
            @if($items->isEmpty())
                <div style="padding:80px 20px;text-align:center;background:#161b22;border:1px dashed #30363d;border-radius:24px;">
                    <div style="font-size:3.5rem;margin-bottom:20px;opacity:0.4;">
                        @if($tab === 'posts') 📝
                        @elseif($tab === 'comments') 💬
                        @else 🔍
                        @endif
                    </div>
                    <p style="color:#f0f6fc;font-weight:700;font-size:1.2rem;margin-bottom:8px;">Nothing to show here</p>
                    <p style="color:#8b949e;font-size:.95rem;">
                        @if($tab === 'posts') This user hasn't created any posts yet.
                        @elseif($tab === 'comments') This user hasn't commented on anything yet.
                        @else No activity found for this tab.
                        @endif
                    </p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:20px;">
                    @foreach($items as $post)
                        <div style="background:#161b22;border:1px solid #30363d;border-radius:20px;padding:24px;position:relative;transition:all .3s ease;"
                             onmouseover="this.style.borderColor='#58a6ff';this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.4)'" 
                             onmouseout="this.style.borderColor='#30363d';this.style.transform='none';this.style.boxShadow='none'">
                            
                            {{-- Metadata --}}
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
                                @if($post->user && $tab !== 'posts')
                                    <span style="font-size:.85rem;color:#8b949e;">by <strong style="color:#c9d1d9;">u/{{ $post->user->name }}</strong></span>
                                    <span style="color:#30363d;">•</span>
                                @endif
                                @if($post->community)
                                    <a href="{{ route('communities.show', $post->community->slug) }}" style="font-size:.85rem;color:#a371f7;font-weight:700;text-decoration:none;background:rgba(163,113,247,0.1);padding:4px 10px;border-radius:6px;">c/{{ $post->community->name }}</a>
                                    <span style="color:#30363d;">•</span>
                                @endif
                                <span style="font-size:.85rem;color:#8b949e;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;margin-right:4px;vertical-align:middle;margin-top:-2px;"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                                
                                <span style="margin-left:auto;font-size:.7rem;font-weight:800;padding:4px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em;
                                    background: {{ ['Question'=>'rgba(139,92,246,.1)','Discussion'=>'rgba(59,130,246,.1)','Tutorial'=>'rgba(16,185,129,.1)','Opinion'=>'rgba(245,158,11,.1)'][$post->intent] ?? 'rgba(100,100,100,.1)' }};
                                    color: {{ ['Question'=>'#a78bfa','Discussion'=>'#60a5fa','Tutorial'=>'#34d399','Opinion'=>'#fbbf24'][$post->intent] ?? '#8b949e' }};
                                    border: 1px solid {{ ['Question'=>'rgba(139,92,246,.2)','Discussion'=>'rgba(59,130,246,.2)','Tutorial'=>'rgba(16,185,129,.2)','Opinion'=>'rgba(245,158,11,.2)'][$post->intent] ?? 'rgba(100,100,100,.2)' }};">
                                    {{ $post->intent }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <a href="{{ route('posts.show', $post) }}" style="display:block;font-size:1.2rem;font-weight:800;color:#f0f6fc;text-decoration:none;line-height:1.5;margin-bottom:16px;"
                               onmouseover="this.style.color='#58a6ff'" onmouseout="this.style.color='#f0f6fc'">
                                {{ $post->title }}
                            </a>

                            {{-- Actions/Stats --}}
                            <div style="display:flex;align-items:center;gap:24px;font-size:.9rem;color:#8b949e;border-top:1px solid #21262d;padding-top:16px;">
                                <div style="display:flex;align-items:center;gap:8px;font-weight:600;color:#c9d1d9;">
                                    <svg width="16" height="16" fill="none" stroke="#2ea043" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                    {{ (int)($post->upvotes ?? 0) - (int)($post->downvotes ?? 0) }}
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;font-weight:600;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $post->comments()->count() }}
                                </div>
                                
                                <div style="margin-left:auto;display:flex;">
                                    <a href="{{ route('posts.show', $post) }}" style="padding:8px 16px;border-radius:30px;background:rgba(255,255,255,0.05);color:#d4d9e0;font-size:.85rem;font-weight:700;text-decoration:none;transition:background .2s;"
                                       onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                                        Read Post <span style="margin-left:4px;">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div style="margin-top:40px;">
                    {{ $items->appends(['tab' => $tab])->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
</x-app-layout>
