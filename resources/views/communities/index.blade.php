@section('title', 'Communities')
<x-app-layout>
    <div class="py-8" style="min-height:100vh;background:#111827;">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 style="font-size:1.8rem;font-weight:800;color:#f3f4f6;margin:0;">Explore Communities</h1>
                    <p style="color:#6b7280;font-size:0.9rem;margin-top:4px;">Find your people. Join discussions that matter to you.</p>
                </div>
                @auth
                <a href="{{ route('communities.create') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Community
                </a>
                @endauth
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($communities as $community)
                <div class="community-card">
                    <!-- Avatar -->
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:800;color:white;flex-shrink:0;">
                            {{ strtoupper(substr($community->name, 0, 1)) }}
                        </div>
                        <div>
                            <a href="{{ route('communities.show', $community->slug) }}" style="font-size:1rem;font-weight:700;color:#f3f4f6;text-decoration:none;display:block;transition:color 0.15s;" onmouseover="this.style.color='#a5b4fc'" onmouseout="this.style.color='#f3f4f6'">c/{{ $community->name }}</a>
                            <span style="font-size:0.75rem;color:#6b7280;">{{ $community->members_count ?? 0 }} members • {{ $community->posts_count ?? 0 }} posts</span>
                        </div>
                    </div>

                    <p style="font-size:0.85rem;color:#9ca3af;line-height:1.6;margin-bottom:16px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;">{{ $community->description }}</p>

                    <!-- Actions -->
                    <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(255,255,255,0.06);padding-top:14px;">
                        <a href="{{ route('communities.show', $community->slug) }}" class="action-btn" style="padding:6px 12px;">View Posts →</a>

                        @auth
                            @if(in_array($community->id, auth()->user()->joined_communities ?? []))
                                <form action="{{ route('communities.leave', $community) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-secondary" style="font-size:0.8rem;padding:6px 14px;">✓ Joined</button>
                                </form>
                            @else
                                <form action="{{ route('communities.join', $community) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary" style="font-size:0.8rem;padding:6px 14px;">+ Join</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
                @empty
                    <div class="col-span-3" style="text-align:center;padding:60px 20px;background:#1e2433;border:1px solid rgba(255,255,255,0.07);border-radius:14px;">
                        <div style="font-size:3rem;margin-bottom:12px;">🏘️</div>
                        <p style="color:#6b7280;font-size:1rem;margin-bottom:16px;">No communities yet. Be the first to create one!</p>
                        @auth
                        <a href="{{ route('communities.create') }}" class="btn-primary" style="display:inline-flex;">Create Community</a>
                        @endauth
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
