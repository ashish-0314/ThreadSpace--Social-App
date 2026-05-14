<x-app-layout>
<div class="home-grid-container" style="display: grid; grid-template-columns: 260px minmax(0, 1fr) 300px; gap: 24px; max-width: 1280px; margin: 0 auto; padding: 32px 16px; align-items: start;">

    <!-- ================== LEFT SIDEBAR ================== -->
    <div class="home-sidebar-left" style="display: flex; flex-direction: column; gap: 16px; position: sticky; top: 90px;">
        @auth
        <!-- Mini Profile Card -->
        <div style="background: #161b22; border: 1px solid #30363d; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;">
            <div style="height: 60px; background: linear-gradient(135deg, #1f6feb, #8b5cf6);"></div>
            <div style="padding: 0 16px 16px; display: flex; flex-direction: column; align-items: center; text-align: center; margin-top: -30px;">
                <a href="{{ route('profile.show', auth()->id()) }}">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" style="width: 64px; height: 64px; border-radius: 50%; border: 4px solid #161b22; object-fit: cover; background: #0d1117;">
                    @else
                        <div style="width: 64px; height: 64px; border-radius: 50%; border: 4px solid #161b22; background: linear-gradient(135deg, #1a8cd8, #0e9a74); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.5rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </a>
                <a href="{{ route('profile.show', auth()->id()) }}" style="color: #f0f6fc; font-weight: 700; font-size: 1.1rem; text-decoration: none; margin-top: 8px;">
                    {{ auth()->user()->name }}
                </a>
                <p style="color: #8b949e; font-size: 0.82rem; margin: 2px 0 12px;">{{ '@' . explode(' ', auth()->user()->name)[0] }}</p>
                
                <div style="display: flex; width: 100%; justify-content: space-between; border-top: 1px solid #30363d; padding-top: 12px;">
                    <div style="display: flex; flex-direction: column;">
                        <span style="color: #f0f6fc; font-weight: 700; font-size: 0.95rem;">{{ $userStats['posts'] ?? 0 }}</span>
                        <span style="color: #8b949e; font-size: 0.75rem;">Posts</span>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="color: #f0f6fc; font-weight: 700; font-size: 0.95rem;">{{ $userStats['followers'] ?? 0 }}</span>
                        <span style="color: #8b949e; font-size: 0.75rem;">Followers</span>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="color: #f0f6fc; font-weight: 700; font-size: 0.95rem;">{{ $userStats['following'] ?? 0 }}</span>
                        <span style="color: #8b949e; font-size: 0.75rem;">Following</span>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Guest Welcome -->
        <div style="background: #161b22; border: 1px solid #30363d; border-radius: 16px; padding: 20px; text-align: center;">
            <h3 style="color: #f0f6fc; font-size: 1.1rem; font-weight: 800; margin-bottom: 8px;">Welcome to ThreadSpace</h3>
            <p style="color: #8b949e; font-size: 0.85rem; margin-bottom: 16px;">Join the community to post, comment, and vote.</p>
            <a href="{{ route('register') }}" class="btn-fill" style="display: block; width: 100%;">Sign Up</a>
            <a href="{{ route('login') }}" class="btn-outline" style="display: block; width: 100%; margin-top: 8px;">Log In</a>
        </div>
        @endauth

        <!-- Navigation Menu -->
        <div style="background: #161b22; border: 1px solid #30363d; border-radius: 16px; padding: 12px 8px;">
            <a href="{{ route('home') }}" class="sidebar-nav-link active">
                <i class="fa-solid fa-house"></i> Home Feed
            </a>
            <a href="{{ route('communities.index') }}" class="sidebar-nav-link">
                <i class="fa-solid fa-compass"></i> Explore Communities
            </a>
            @auth
            @if(!auth()->user()->isAdmin())
            <a href="{{ route('profile.show', auth()->id()) }}" class="sidebar-nav-link">
                <i class="fa-regular fa-file-lines"></i> My Posts
            </a>
            @endif
            @endauth
        </div>
    </div>


    <!-- ================== CENTER COLUMN (FEED) ================== -->
    <div class="home-feed-center" style="display: flex; flex-direction: column; gap: 16px;">
        <!-- Sort Tabs & Create Post -->
        <div style="background: #161b22; border: 1px solid #30363d; border-radius: 16px; padding: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div class="sort-tabs" style="margin: 0;">
                <a href="{{ route('home', ['sort'=>'latest']) }}"  class="sort-tab {{ $sort==='latest'  ? 'active':'' }}"><i class="fa-regular fa-clock" style="margin-right:5px;"></i>New</a>
                <a href="{{ route('home', ['sort'=>'top']) }}"     class="sort-tab {{ $sort==='top'     ? 'active':'' }}"><i class="fa-solid fa-fire" style="margin-right:5px;"></i>Top</a>
                <a href="{{ route('home', ['sort'=>'trending']) }}" class="sort-tab {{ $sort==='trending'? 'active':'' }}"><i class="fa-solid fa-arrow-trend-up" style="margin-right:5px;"></i>Trending</a>
            </div>
            @auth
            @if(!auth()->user()->isAdmin())
            <a href="{{ route('posts.create.standalone') }}" class="btn-fill" style="font-size:.8rem; padding: 6px 12px;">
                <i class="fa-solid fa-plus" style="margin-right: 4px;"></i> Create Post
            </a>
            @endif
            @endauth
        </div>

        <!-- Posts -->
        <div style="display:flex;flex-direction:column;gap:16px;">
            @forelse($posts as $post)
                @include('posts.partials.post-card', ['post' => $post])
            @empty
                <div style="text-align:center;padding:60px 20px;background:#161b22;border:1px solid #30363d;border-radius:16px;">
                    <i class="fa-solid fa-ghost" style="font-size:2.5rem;color:#30363d;display:block;margin-bottom:14px;"></i>
                    <p style="color:#f0f6fc;font-weight:700;font-size:1.1rem;margin-bottom:8px;">It's awfully quiet here...</p>
                    <p style="color:#8b949e;font-size:.9rem;margin-bottom:24px;">Join more communities to fill up your feed!</p>
                    <a href="{{ route('communities.index') }}" class="btn-fill" style="padding: 10px 24px;">Browse Communities</a>
                </div>
            @endforelse
        </div>

        <div style="margin-top:8px;">{{ $posts->links() }}</div>
    </div>


    <!-- ================== RIGHT SIDEBAR ================== -->
    <div class="home-sidebar-right" style="display: flex; flex-direction: column; gap: 16px; position: sticky; top: 90px;">
        
        <!-- Trending Communities -->
        @if(isset($trendingCommunities) && $trendingCommunities->isNotEmpty())
        <div style="background: #161b22; border: 1px solid #30363d; border-radius: 16px; overflow: hidden;">
            <div style="padding: 16px; border-bottom: 1px solid #21262d;">
                <h3 style="color: #f0f6fc; font-weight: 800; font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-arrow-trend-up" style="color: #58a6ff;"></i> Trending Communities
                </h3>
            </div>
            <div style="display: flex; flex-direction: column;">
                @foreach($trendingCommunities as $comm)
                    <a href="{{ route('communities.show', $comm->slug) }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #a855f7, #6366f1); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1rem; flex-shrink: 0;">
                            {{ strtoupper(substr($comm->name, 0, 1)) }}
                        </div>
                        <div style="flex: 1; overflow: hidden;">
                            <h4 style="color: #f0f6fc; font-size: 0.9rem; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $comm->name }}</h4>
                            <p style="color: #8b949e; font-size: 0.75rem; margin: 2px 0 0;">{{ $comm->members_count ?? 0 }} Members</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div style="padding: 12px 16px; border-top: 1px solid #21262d;">
                <a href="{{ route('communities.index') }}" style="color: #58a6ff; font-size: 0.85rem; font-weight: 600; text-decoration: none;">View all communities →</a>
            </div>
        </div>
        @endif

        <!-- Suggested Users -->
        @if(isset($suggestedUsers) && $suggestedUsers->isNotEmpty() && (!auth()->check() || !auth()->user()->isAdmin()))
        <div style="background: #161b22; border: 1px solid #30363d; border-radius: 16px; overflow: hidden;">
            <div style="padding: 16px; border-bottom: 1px solid #21262d;">
                <h3 style="color: #f0f6fc; font-weight: 800; font-size: 1rem; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-users" style="color: #a855f7;"></i> Who to follow
                </h3>
            </div>
            <div style="display: flex; flex-direction: column;">
                @foreach($suggestedUsers as $sUser)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; transition: background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">
                        <a href="{{ route('profile.show', $sUser->id) }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none; overflow: hidden; flex: 1;">
                            @if($sUser->avatar_url)
                                <img src="{{ $sUser->avatar_url }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                            @else
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #1a8cd8, #0e9a74); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.9rem; flex-shrink: 0;">
                                    {{ strtoupper(substr($sUser->name, 0, 1)) }}
                                </div>
                            @endif
                            <div style="overflow: hidden;">
                                <h4 style="color: #f0f6fc; font-size: 0.85rem; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $sUser->name }}</h4>
                                <p style="color: #8b949e; font-size: 0.75rem; margin: 2px 0 0;">{{ is_array($sUser->reputation) ? array_sum($sUser->reputation) : (is_numeric($sUser->reputation) ? $sUser->reputation : 0) }} Rep</p>
                            </div>
                        </a>
                        @auth
                            <form method="POST" action="{{ route('network.follow', $sUser->id) }}">
                                @csrf
                                <button type="submit" style="padding: 4px 10px; border-radius: 12px; background: #f0f6fc; color: #0d1117; font-size: 0.75rem; font-weight: 700; border: none; cursor: pointer; margin-left: 8px;">
                                    Follow
                                </button>
                            </form>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Footer / Links -->
        <div style="padding: 0 16px; text-align: left;">
            <p style="color: #6b7280; font-size: 0.75rem; line-height: 1.6; margin: 0;">
                <a href="#" style="color: inherit; text-decoration: none; margin-right: 8px;">About</a>
                <a href="#" style="color: inherit; text-decoration: none; margin-right: 8px;">Help</a>
                <a href="#" style="color: inherit; text-decoration: none; margin-right: 8px;">Privacy Policy</a>
                <a href="#" style="color: inherit; text-decoration: none; margin-right: 8px;">Terms</a><br>
                ThreadSpace © {{ date('Y') }}. Built for developers.
            </p>
        </div>

    </div>

</div>

<style>
/* CSS overrides specifically for the home grid */
@media (max-width: 1024px) {
    .home-grid-container {
        grid-template-columns: 240px minmax(0, 1fr) !important;
    }
    .home-sidebar-right {
        display: none !important;
    }
}
@media (max-width: 768px) {
    .home-grid-container {
        grid-template-columns: 1fr !important;
        padding: 16px 8px !important;
    }
    .home-sidebar-left {
        display: none !important;
    }
}
.sidebar-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #c9d1d9;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.2s;
    margin-bottom: 2px;
}
.sidebar-nav-link:hover {
    background: #21262d;
    color: #f0f6fc;
}
.sidebar-nav-link.active {
    background: rgba(47, 129, 247, 0.1);
    color: #58a6ff;
}
.sidebar-nav-link i {
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}
</style>
</x-app-layout>
