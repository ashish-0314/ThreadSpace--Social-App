<x-app-layout>
    @section('title', 'Admin Dashboard')

    <div style="max-width:1200px;margin:40px auto;padding:0 20px;">
        <!-- Header & Nav -->
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:32px;border-bottom:1px solid #30363d;padding-bottom:20px;flex-wrap:wrap;gap:16px;">
            <div>
                <h1 style="font-size:2rem;font-weight:700;color:#f0f6fc;margin:0 0 8px;letter-spacing:-0.5px;">Overview</h1>
                <p style="color:#8b949e;font-size:0.95rem;margin:0;">System metrics and high-level statistics.</p>
            </div>
            <div style="display:flex;gap:12px;">
                <a href="{{ route('admin.dashboard') }}" style="padding:8px 16px;background:#58a6ff;color:#0d1117;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;">Overview</a>
                <a href="{{ route('admin.users') }}" style="padding:8px 16px;background:transparent;border:1px solid #30363d;color:#c9d1d9;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">Users</a>
                <a href="{{ route('admin.posts') }}" style="padding:8px 16px;background:transparent;border:1px solid #30363d;color:#c9d1d9;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">Posts</a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:24px;">
            <!-- Total Users Card -->
            <div style="background:#0d1117;border:1px solid #30363d;border-radius:16px;padding:32px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;width:4px;height:100%;background:#58a6ff;"></div>
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
                    <div style="width:48px;height:48px;border-radius:12px;background:rgba(88, 166, 255, 0.1);display:flex;align-items:center;justify-content:center;color:#58a6ff;">
                        <i class="fa-solid fa-users" style="font-size:1.4rem;"></i>
                    </div>
                    <h3 style="font-size:1rem;color:#8b949e;font-weight:500;margin:0;">Total Users</h3>
                </div>
                <div style="font-size:3.5rem;font-weight:700;color:#f0f6fc;line-height:1;">{{ number_format($stats['users']) }}</div>
            </div>

            <!-- Total Posts Card -->
            <div style="background:#0d1117;border:1px solid #30363d;border-radius:16px;padding:32px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;width:4px;height:100%;background:#3fb950;"></div>
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
                    <div style="width:48px;height:48px;border-radius:12px;background:rgba(63, 185, 80, 0.1);display:flex;align-items:center;justify-content:center;color:#3fb950;">
                        <i class="fa-solid fa-file-lines" style="font-size:1.4rem;"></i>
                    </div>
                    <h3 style="font-size:1rem;color:#8b949e;font-weight:500;margin:0;">Total Posts</h3>
                </div>
                <div style="font-size:3.5rem;font-weight:700;color:#f0f6fc;line-height:1;">{{ number_format($stats['posts']) }}</div>
            </div>

            <!-- Total Communities Card -->
            <div style="background:#0d1117;border:1px solid #30363d;border-radius:16px;padding:32px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;width:4px;height:100%;background:#a371f7;"></div>
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
                    <div style="width:48px;height:48px;border-radius:12px;background:rgba(163, 113, 247, 0.1);display:flex;align-items:center;justify-content:center;color:#a371f7;">
                        <i class="fa-solid fa-compass" style="font-size:1.4rem;"></i>
                    </div>
                    <h3 style="font-size:1rem;color:#8b949e;font-weight:500;margin:0;">Communities</h3>
                </div>
                <div style="font-size:3.5rem;font-weight:700;color:#f0f6fc;line-height:1;">{{ number_format($stats['communities']) }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
