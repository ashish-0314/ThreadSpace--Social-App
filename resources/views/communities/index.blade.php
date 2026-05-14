@section('title', 'Explore Communities')
<x-app-layout>
    <div style="min-height:100vh;padding:40px 0;">
        <div style="max-width:1200px;margin:0 auto;padding:0 20px;">

            <!-- Header -->
            <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;margin-bottom:32px;gap:16px;">
                <div>
                    <h1 style="font-size:2rem;font-weight:800;color:#f0f6fc;margin:0 0 4px;letter-spacing:-0.5px;">Explore Communities</h1>
                    <p style="color:#8b949e;font-size:0.95rem;margin:0;">Find your people. Join discussions that matter to you.</p>
                </div>
                @auth
                    @if(!auth()->user()->isAdmin())
                    <a href="{{ route('communities.create') }}" class="btn-fill" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;font-size:0.9rem;">
                        <i class="fa-solid fa-plus"></i> Create Community
                    </a>
                    @endif
                @endauth
            </div>

            <!-- Grid -->
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:24px;">
                @forelse($communities as $community)
                <div style="background:#161b22;border:1px solid #30363d;border-radius:16px;padding:24px;transition:all 0.2s;" onmouseover="this.style.borderColor='#484f58';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='#30363d';this.style.transform='none'">
                    <!-- Avatar & Title -->
                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
                        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#a855f7,#6366f1);display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:white;flex-shrink:0;">
                            {{ strtoupper(substr($community->name, 0, 1)) }}
                        </div>
                        <div style="overflow:hidden;">
                            <a href="{{ route('communities.show', $community->slug) }}" style="font-size:1.1rem;font-weight:700;color:#f0f6fc;text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;transition:color 0.2s;" onmouseover="this.style.color='#58a6ff'" onmouseout="this.style.color='#f0f6fc'">c/{{ $community->name }}</a>
                            <span style="font-size:0.8rem;color:#8b949e;">{{ $community->members_count ?? 0 }} members • {{ $community->posts_count ?? 0 }} posts</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p style="font-size:0.9rem;color:#c9d1d9;line-height:1.6;margin-bottom:24px;height:4.8em;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;">{{ $community->description }}</p>

                    <!-- Actions -->
                    <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #21262d;padding-top:16px;">
                        <a href="{{ route('communities.show', $community->slug) }}" style="color:#58a6ff;font-size:0.85rem;font-weight:600;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#79c0ff'" onmouseout="this.style.color='#58a6ff'">View Posts →</a>

                        @auth
                            @if(!auth()->user()->isAdmin())
                                @if(in_array($community->id, auth()->user()->joined_communities ?? []))
                                    <form action="{{ route('communities.leave', $community) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background:transparent;border:1px solid #30363d;color:#8b949e;padding:6px 16px;border-radius:8px;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.borderColor='#f85149';this.style.color='#f85149'" onmouseout="this.style.borderColor='#30363d';this.style.color='#8b949e'">Leave</button>
                                    </form>
                                @else
                                    <form action="{{ route('communities.join', $community) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background:#21262d;border:1px solid #30363d;color:#c9d1d9;padding:6px 16px;border-radius:8px;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#30363d';this.style.color='#f0f6fc'" onmouseout="this.style.background='#21262d';this.style.color='#c9d1d9'">Join</button>
                                    </form>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>
                @empty
                    <div style="grid-column:1 / -1;text-align:center;padding:80px 20px;background:#161b22;border:1px solid #30363d;border-radius:16px;">
                        <i class="fa-solid fa-compass" style="font-size:3.5rem;color:#30363d;display:block;margin-bottom:20px;"></i>
                        <p style="color:#f0f6fc;font-weight:700;font-size:1.25rem;margin-bottom:8px;">No communities found</p>
                        <p style="color:#8b949e;font-size:.95rem;margin-bottom:24px;">Be the first to start a new community and gather people together!</p>
                        @auth
                            @if(!auth()->user()->isAdmin())
                            <a href="{{ route('communities.create') }}" class="btn-fill" style="display:inline-flex;padding:10px 24px;font-size:0.95rem;">
                                <i class="fa-solid fa-plus" style="margin-right:8px;"></i> Create Community
                            </a>
                            @endif
                        @endauth
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
