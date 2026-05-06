<x-app-layout>
<div style="max-width:760px;margin:0 auto;padding:24px 16px;">

    <!-- Sort Tabs -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <div class="sort-tabs">
            <a href="{{ route('home', ['sort'=>'latest']) }}"  class="sort-tab {{ $sort==='latest'  ? 'active':'' }}"><i class="fa-regular fa-clock" style="margin-right:5px;"></i>New</a>
            <a href="{{ route('home', ['sort'=>'top']) }}"     class="sort-tab {{ $sort==='top'     ? 'active':'' }}"><i class="fa-solid fa-fire" style="margin-right:5px;"></i>Top</a>
            <a href="{{ route('home', ['sort'=>'trending']) }}" class="sort-tab {{ $sort==='trending'? 'active':'' }}"><i class="fa-solid fa-arrow-trend-up" style="margin-right:5px;"></i>Trending</a>
        </div>
        @auth
        <a href="{{ route('communities.create') }}" class="btn-fill" style="font-size:.8rem;">+ Create</a>
        @endauth
    </div>

    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#86efac;padding:10px 16px;border-radius:8px;font-size:.85rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Posts -->
    <div style="display:flex;flex-direction:column;gap:12px;">
        @forelse($posts as $post)
            @include('posts.partials.post-card', ['post' => $post])
        @empty
            <div style="text-align:center;padding:60px 20px;background:#161b22;border:1px solid #21262d;border-radius:12px;">
                <i class="fa-solid fa-globe" style="font-size:2.5rem;color:#30363d;display:block;margin-bottom:14px;"></i>
                <p style="color:#6b7280;margin-bottom:16px;">No posts yet. Join communities to see posts here!</p>
                <a href="{{ route('communities.index') }}" class="btn-fill">Browse Communities</a>
            </div>
        @endforelse
    </div>

    <div style="margin-top:24px;">{{ $posts->links() }}</div>
</div>
</x-app-layout>
