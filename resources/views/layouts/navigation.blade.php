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
        <div class="ts-nav-links hidden sm:flex">
            <a href="{{ route('home') }}" class="ts-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('communities.index') }}" class="ts-nav-link {{ request()->routeIs('communities.*') ? 'active' : '' }}">Communities</a>
        </div>

        <!-- Right -->
        <div class="ts-nav-right">
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="nav-user-chip">
                            <div class="nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            {{ Auth::user()->name }}
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
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

            <!-- Mobile toggle -->
            <button @click="open = !open" class="sm:hidden" style="background:transparent;border:none;color:#8b949e;cursor:pointer;padding:4px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path :class="{'hidden':open,'inline-flex':!open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden':!open,'inline-flex':open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block':open,'hidden':!open}" class="hidden sm:hidden" style="border-top:1px solid #21262d;">
        <div style="padding:10px 16px;display:flex;flex-direction:column;gap:2px;">
            <a href="{{ route('home') }}" class="ts-nav-link" style="border-radius:8px;">Home</a>
            <a href="{{ route('communities.index') }}" class="ts-nav-link" style="border-radius:8px;">Communities</a>
            @auth
            <div style="border-top:1px solid #21262d;margin-top:6px;padding-top:6px;display:flex;flex-direction:column;gap:2px;">
                <a href="{{ route('profile.edit') }}" class="ts-nav-link" style="border-radius:8px;">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ts-nav-link" style="border-radius:8px;border:none;background:transparent;cursor:pointer;width:100%;text-align:left;">Log Out</button>
                </form>
            </div>
            @else
            <div style="border-top:1px solid #21262d;margin-top:6px;padding-top:6px;display:flex;gap:8px;">
                <a href="{{ route('login') }}" class="btn-outline" style="flex:1;text-align:center;">Log In</a>
                <a href="{{ route('register') }}" class="btn-fill" style="flex:1;text-align:center;">Sign Up</a>
            </div>
            @endauth
        </div>
    </div>
</nav>
