<x-app-layout>
    <!-- Community Banner -->
    <div class="community-banner">
        <div style="max-width:1100px;margin:0 auto;padding:0 20px;">
            <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:12px;padding-bottom:20px;">
                <div style="display:flex;align-items:center;gap:14px;">
                    <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;color:#fff;box-shadow:0 4px 16px rgba(26,140,216,.3);">
                        {{ strtoupper(substr($community->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 style="font-size:1.4rem;font-weight:800;color:#f0f6fc;margin:0;">c/{{ $community->name }}</h1>
                        <p style="color:#8b949e;font-size:.82rem;margin:2px 0 0;">{{ $community->members_count ?? 0 }} members</p>
                    </div>
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    @auth
                        @if(in_array($community->id, auth()->user()->joined_communities ?? []))
                            <form action="{{ route('communities.leave', $community) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-outline" style="color:#8b949e;border-color:#30363d;">✓ Joined</button>
                            </form>
                        @else
                            <form action="{{ route('communities.join', $community) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-fill">Join</button>
                            </form>
                        @endif
                        <a href="{{ route('communities.posts.create', $community) }}" class="btn-fill">+ Create Post</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-fill">Join</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div style="max-width:1100px;margin:0 auto;padding:24px 20px;">
        <div style="display:grid;grid-template-columns:1fr 300px;gap:24px;" class="comm-layout">

            <!-- Posts Feed -->
            <div style="display:flex;flex-direction:column;gap:12px;">
                @forelse($posts as $post)
                    @include('posts.partials.post-card', ['post' => $post])
                @empty
                    <div style="text-align:center;padding:60px 20px;background:#161b22;border:1px solid #21262d;border-radius:12px;">
                        <div style="font-size:2.5rem;margin-bottom:10px;">📝</div>
                        <p style="color:#6b7280;margin-bottom:16px;">No posts yet. Be the first!</p>
                        @auth
                        <a href="{{ route('communities.posts.create', $community) }}" class="btn-fill">Create Post</a>
                        @endauth
                    </div>
                @endforelse
                <div>{{ $posts->links() }}</div>
            </div>

            <!-- Sidebar -->
            <div>
                <div class="sidebar-box" style="position:sticky;top:72px;">
                    <div class="sidebar-box-header">About Community</div>
                    <div class="sidebar-box-body">
                        <p style="font-size:.86rem;color:#8b949e;line-height:1.7;margin:0 0 14px;">{{ $community->description }}</p>
                        <div style="display:flex;gap:20px;font-size:.82rem;color:#8b949e;margin-bottom:14px;">
                            <div>
                                <div style="font-size:1.1rem;font-weight:700;color:#f0f6fc;">{{ $community->members_count ?? 0 }}</div>
                                <div>Members</div>
                            </div>
                            <div>
                                <div style="font-size:1.1rem;font-weight:700;color:#f0f6fc;">{{ $posts->total() }}</div>
                                <div>Posts</div>
                            </div>
                        </div>
                        @if($community->rules)
                        <div style="border-top:1px solid #21262d;padding-top:12px;margin-top:4px;">
                            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;margin-bottom:8px;">Rules</div>
                            <div style="font-size:.83rem;color:#8b949e;white-space:pre-line;line-height:1.7;">{{ $community->rules }}</div>
                        </div>
                        @endif
                        <div style="border-top:1px solid #21262d;padding-top:12px;margin-top:12px;font-size:.78rem;color:#6b7280;">
                            Created {{ $community->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>@media(max-width:768px){.comm-layout{grid-template-columns:1fr !important;}}</style>
</x-app-layout>
