<nav class="ts-nav" x-data="{ open: false }">
    <div class="ts-nav-inner">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="ts-logo">
            <div class="ts-logo-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
            ThreadSpace
        </a>

        <!-- Nav Links -->
        <div class="ts-nav-links">
            <a href="{{ route('home') }}" class="ts-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" title="Home">
                <i class="fa-solid fa-house"></i>
            </a>
            @auth
            @if(!Auth::user()->isAdmin())
            @php
                $unreadMsgs = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
            @endphp
            <a href="{{ route('messages.index') }}" class="ts-nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}" style="position:relative;" title="Messages">
                <i class="fa-solid fa-envelope" style="font-size: 1.1rem;"></i>
                @if($unreadMsgs > 0)
                    <span style="position:absolute;top:-2px;right:-4px;width:16px;height:16px;background:#f85149;border-radius:50%;color:white;font-size:9px;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #0d1117;">
                        {{ $unreadMsgs > 9 ? '9+' : $unreadMsgs }}
                    </span>
                @endif
            </a>
            @endif
            @endauth
        </div>

        <x-global-search />

        <!-- Right -->
        <div class="ts-nav-right">
            @auth
                @if(!Auth::user()->isAdmin())
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="btn-fill" style="padding:6px 16px;font-size:.82rem;font-weight:700;display:inline-flex;align-items:center;gap:5px;border:none;cursor:pointer;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Create
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-left:2px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('posts.create.standalone')">
                            <i class="fa-solid fa-pen-nib" style="margin-right:6px;width:14px;text-align:center;"></i> Post
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('communities.create')">
                            <i class="fa-solid fa-users" style="margin-right:6px;width:14px;text-align:center;"></i> Community
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>

                @php
                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                
                <a href="{{ route('notifications.index') }}" class="ts-nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" style="position:relative;" title="Notifications">
                    <i class="fa-solid fa-bell" style="font-size: 1.1rem;"></i>
                    @if($unreadCount > 0)
                        <span style="position:absolute;top:-2px;right:-4px;width:16px;height:16px;background:#f85149;border-radius:50%;color:white;font-size:9px;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #0d1117;">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>
                @endif
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="nav-user-chip">
                            @if(Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" class="nav-avatar" style="object-fit:cover;border:none;">
                            @else
                                <div class="nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            @endif
                            {{ Auth::user()->name }}
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @if(Auth::user()->isAdmin())
                            <x-dropdown-link :href="route('admin.dashboard')" style="color:#58a6ff;font-weight:700;">
                                <i class="fa-solid fa-shield-halved" style="margin-right:6px;"></i> Admin Panel
                            </x-dropdown-link>
                            <div style="border-top:1px solid #30363d;margin:4px 0;"></div>
                        @else
                            <x-dropdown-link :href="route('profile.show', Auth::user()->id)">My Profile</x-dropdown-link>
                        @endif
                        <x-dropdown-link :href="route('profile.edit')">Settings</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}" class="btn-outline">Log In</a>
                <a href="{{ route('register') }}" class="btn-fill">Sign Up</a>
            @endauth

            <!-- Mobile toggle: uses .nav-mobile-toggle class hidden via CSS media query -->
            <button @click.prevent.stop="open = !open" class="nav-mobile-toggle" style="background:transparent;border:none;color:#8b949e;cursor:pointer;padding:4px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path :class="{'hidden':open,'inline-flex':!open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden':!open,'inline-flex':open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu: hidden via CSS media query on desktop -->
    <div x-show="open" 
         @click.outside="open = false"
         x-cloak
         x-transition
         class="nav-mobile-menu" 
         style="border-top:1px solid #21262d; z-index: 100;">
        <div style="padding:10px 16px;display:flex;flex-direction:column;gap:2px;">
            <a href="{{ route('home') }}" class="ts-nav-link" style="border-radius:8px;">Home</a>
            <a href="{{ route('communities.index') }}" class="ts-nav-link" style="border-radius:8px;">Communities</a>
            @auth
            @if(!Auth::user()->isAdmin())
            <div style="margin: 6px 0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #8b949e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; padding: 0 8px;">Create</div>
                <a href="{{ route('posts.create.standalone') }}" class="ts-nav-link" style="border-radius:8px;">
                    <i class="fa-solid fa-pen-nib" style="margin-right:6px;width:14px;text-align:center;"></i> Post
                </a>
                <a href="{{ route('communities.create') }}" class="ts-nav-link" style="border-radius:8px;">
                    <i class="fa-solid fa-users" style="margin-right:6px;width:14px;text-align:center;"></i> Community
                </a>
            </div>
            @endif
            <div style="border-top:1px solid #21262d;margin-top:6px;padding-top:6px;display:flex;flex-direction:column;gap:2px;">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="ts-nav-link" style="border-radius:8px;color:#58a6ff;font-weight:700;">
                        <i class="fa-solid fa-shield-halved" style="margin-right:6px;"></i> Admin Panel
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="ts-nav-link" style="border-radius:8px;">Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ts-nav-link" style="border-radius:8px;border:none;background:transparent;cursor:pointer;width:100%;text-align:left;">Log Out</button>
                </form>
            </div>
            @else
            {{-- Guest buttons: inline styled to prevent Tailwind override --}}
            <div style="border-top:1px solid #21262d;margin-top:6px;padding-top:8px;display:flex;gap:8px;">
                <a href="{{ route('login') }}"
                   style="flex:1;text-align:center;display:inline-flex;align-items:center;justify-content:center;
                          padding:7px 12px;border-radius:999px;font-size:.83rem;font-weight:700;
                          color:#58a6ff;background:transparent;border:1px solid #58a6ff;text-decoration:none;">
                    Log In
                </a>
                <a href="{{ route('register') }}"
                   style="flex:1;text-align:center;display:inline-flex;align-items:center;justify-content:center;
                          padding:7px 12px;border-radius:999px;font-size:.83rem;font-weight:700;
                          color:#0d1117;background:#58a6ff;border:none;text-decoration:none;">
                    Sign Up
                </a>
            </div>
            @endauth
        </div>
    </div>
</nav>
