<div style="width: 320px; background: #161b22; border: 1px solid #30363d; border-radius: 16px; padding: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); cursor: default;" @click.stop>
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        {{-- Avatar --}}
        <a href="{{ route('profile.show', $user) }}" style="display:block;">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 2px solid #0d1117;">
            @else
                <div style="width: 56px; height: 56px; border-radius: 50%; border: 2px solid #0d1117; background: #21262d; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; color: #8b949e;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </a>

        {{-- Actions --}}
        @auth
            @if(auth()->id() !== $user->id)
                <form method="POST" action="{{ route('network.follow', $user) }}">
                    @csrf
                    <button type="submit" style="padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: .85rem; cursor: pointer; transition: all .2s;
                        {{ $isFollowing ? 'background: transparent; border: 1px solid #30363d; color: #f0f6fc;' : 'background: #f0f6fc; border: 1px solid #f0f6fc; color: #0d1117;' }}">
                        {{ $isFollowing ? 'Following' : 'Follow' }}
                    </button>
                </form>
            @endif
        @endauth
    </div>

    {{-- User Info --}}
    <div style="margin-bottom: 12px;">
        <a href="{{ route('profile.show', $user) }}" style="font-size: 1.1rem; font-weight: 800; color: #f0f6fc; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
            {{ $user->name }}
        </a>
        <div style="font-size: .9rem; color: #8b949e;">u/{{ $user->name }}</div>
    </div>

    {{-- Bio --}}
    @if($user->bio)
        <div style="font-size: .88rem; color: #d4d9e0; line-height: 1.4; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
            {{ $user->bio }}
        </div>
    @endif

    {{-- Stats --}}
    <div style="display: flex; gap: 16px; font-size: .85rem; color: #8b949e;">
        <div><strong style="color: #f0f6fc;">{{ $followingCount }}</strong> Following</div>
        <div><strong style="color: #f0f6fc;">{{ $followersCount }}</strong> Followers</div>
    </div>
</div>
