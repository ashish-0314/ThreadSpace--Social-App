<x-app-layout>
<div style="max-width:860px;margin:0 auto;padding:32px 16px;">

    {{-- Search Header --}}
    <div style="margin-bottom:28px;">
        <h1 style="font-size:1.5rem;font-weight:800;color:#f0f6fc;margin:0 0 16px;display:flex;align-items:center;gap:10px;">
            <i class="fa-solid fa-magnifying-glass" style="color:#58a6ff;"></i>
            Search ThreadSpace
        </h1>

        <form method="GET" action="{{ route('search') }}" style="display:flex;gap:10px;">
            <input type="text"
                   name="q"
                   value="{{ $query }}"
                   placeholder="Search posts, communities, users..."
                   autofocus
                   style="flex:1;background:#161b22;border:1px solid #30363d;border-radius:10px;padding:12px 16px;font-size:.95rem;color:#f0f6fc;outline:none;transition:border-color .2s;"
                   onfocus="this.style.borderColor='#58a6ff'" onblur="this.style.borderColor='#30363d'">
            <button type="submit" style="padding:12px 22px;border-radius:10px;background:#58a6ff;color:#0d1117;font-weight:700;font-size:.9rem;border:none;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='#79b8ff'" onmouseout="this.style.background='#58a6ff'">
                <i class="fa-solid fa-magnifying-glass"></i> Search
            </button>
        </form>
    </div>

    @if(empty($query))
        {{-- Empty / Welcome State --}}
        <div style="text-align:center;padding:80px 20px;background:#161b22;border:1px solid #21262d;border-radius:16px;">
            <i class="fa-solid fa-magnifying-glass" style="font-size:3rem;color:#30363d;display:block;margin-bottom:16px;"></i>
            <p style="color:#8b949e;font-size:1rem;">Type something above to search posts, communities, and users.</p>
        </div>
    @else
        {{-- Results Summary --}}
        <p style="color:#6b7280;font-size:.85rem;margin-bottom:24px;">
            Showing results for <strong style="color:#d4d9e0;">"{{ $query }}"</strong>
        </p>

        <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

            {{-- Left Column: Posts --}}
            <div>
                <h2 style="font-size:1rem;font-weight:700;color:#8b949e;margin:0 0 12px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-newspaper"></i> Posts
                </h2>

                @forelse($posts as $post)
                    <a href="{{ route('posts.show', $post) }}" style="display:block;background:#161b22;border:1px solid #21262d;border-radius:12px;padding:14px 16px;margin-bottom:10px;text-decoration:none;transition:border-color .2s;" onmouseover="this.style.borderColor='#58a6ff55'" onmouseout="this.style.borderColor='#21262d'">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                            <span class="pc-flair pc-flair-{{ $post->intent }}" style="margin-bottom:0;">{{ $post->intent }}</span>
                            @if($post->community)
                                <span style="font-size:.75rem;color:#58a6ff;font-weight:600;">c/{{ $post->community->name }}</span>
                            @endif
                        </div>
                        <div style="font-size:.95rem;font-weight:700;color:#f0f6fc;line-height:1.4;margin-bottom:6px;">{{ $post->title }}</div>
                        <div style="font-size:.78rem;color:#6b7280;display:flex;align-items:center;gap:12px;">
                            <span><i class="fa-regular fa-user" style="margin-right:3px;"></i>{{ $post->user->name ?? 'Unknown' }}</span>
                            <span><i class="fa-regular fa-clock" style="margin-right:3px;"></i>{{ $post->created_at->diffForHumans() }}</span>
                            <span><i class="fa-solid fa-arrow-up" style="margin-right:3px;"></i>{{ (int)($post->upvotes ?? 0) - (int)($post->downvotes ?? 0) }}</span>
                        </div>
                    </a>
                @empty
                    <div style="background:#161b22;border:1px solid #21262d;border-radius:12px;padding:24px;text-align:center;color:#6b7280;font-size:.88rem;">
                        <i class="fa-solid fa-circle-xmark" style="display:block;font-size:1.5rem;margin-bottom:8px;color:#30363d;"></i>
                        No posts found for "{{ $query }}"
                    </div>
                @endforelse
            </div>

            {{-- Right Column: Communities + Users --}}
            <div style="display:flex;flex-direction:column;gap:16px;">

                {{-- Communities --}}
                <div style="background:#161b22;border:1px solid #21262d;border-radius:12px;overflow:hidden;">
                    <div style="padding:12px 16px;border-bottom:1px solid #21262d;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-people-group" style="color:#a371f7;"></i>
                        <span style="font-size:.88rem;font-weight:700;color:#f0f6fc;">Communities</span>
                    </div>
                    @forelse($communities as $community)
                        <a href="{{ route('communities.show', $community->slug) }}" style="display:flex;align-items:center;gap:10px;padding:10px 16px;text-decoration:none;border-bottom:1px solid #21262d;transition:background .15s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#a371f7,#58a6ff);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:.85rem;flex-shrink:0;">
                                {{ strtoupper(substr($community->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:.88rem;font-weight:700;color:#f0f6fc;">c/{{ $community->name }}</div>
                                @if($community->description)
                                    <div style="font-size:.75rem;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">{{ $community->description }}</div>
                                @endif
                            </div>
                        </a>
                    @empty
                        <p style="padding:16px;color:#6b7280;font-size:.85rem;text-align:center;">No communities found</p>
                    @endforelse
                </div>

                {{-- Users --}}
                <div style="background:#161b22;border:1px solid #21262d;border-radius:12px;overflow:hidden;">
                    <div style="padding:12px 16px;border-bottom:1px solid #21262d;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-users" style="color:#58a6ff;"></i>
                        <span style="font-size:.88rem;font-weight:700;color:#f0f6fc;">Users</span>
                    </div>
                    @forelse($users as $user)
                        <a href="{{ route('profile.show', $user) }}" style="display:flex;align-items:center;gap:10px;padding:10px 16px;text-decoration:none;border-bottom:1px solid #21262d;transition:background .15s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                            @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                            @else
                                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:.85rem;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-size:.88rem;font-weight:700;color:#f0f6fc;">{{ $user->name }}</div>
                                <div style="font-size:.75rem;color:#6b7280;">u/{{ $user->name }}</div>
                            </div>
                        </a>
                    @empty
                        <p style="padding:16px;color:#6b7280;font-size:.85rem;text-align:center;">No users found</p>
                    @endforelse
                </div>

            </div>
        </div>
    @endif
</div>
</x-app-layout>
