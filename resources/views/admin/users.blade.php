<x-app-layout>
    @section('title', 'Admin Users')

    <div style="max-width:1200px;margin:40px auto;padding:0 20px;" x-data="{ deleteModalOpen: false, deleteUserId: null, deleteUserName: '' }">
        
        <!-- Header & Nav -->
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:32px;border-bottom:1px solid #30363d;padding-bottom:20px;flex-wrap:wrap;gap:16px;">
            <div>
                <h1 style="font-size:2rem;font-weight:700;color:#f0f6fc;margin:0 0 8px;letter-spacing:-0.5px;">Users Management</h1>
                <p style="color:#8b949e;font-size:0.95rem;margin:0;">View and manage registered accounts.</p>
            </div>
            <div style="display:flex;gap:12px;">
                <a href="{{ route('admin.dashboard') }}" style="padding:8px 16px;background:transparent;border:1px solid #30363d;color:#c9d1d9;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">Overview</a>
                <a href="{{ route('admin.users') }}" style="padding:8px 16px;background:#58a6ff;color:#0d1117;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;">Users</a>
                <a href="{{ route('admin.posts') }}" style="padding:8px 16px;background:transparent;border:1px solid #30363d;color:#c9d1d9;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">Posts</a>
            </div>
        </div>

        <!-- Search Bar -->
        <div style="margin-bottom:24px;display:flex;justify-content:flex-end;">
            <form action="{{ route('admin.users') }}" method="GET" style="display:flex;gap:10px;width:100%;max-width:400px;">
                <div style="position:relative;flex:1;">
                    <i class="fa-solid fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#8b949e;font-size:0.9rem;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." style="width:100%;background:#0d1117;border:1px solid #30363d;border-radius:8px;padding:10px 14px 10px 40px;color:#c9d1d9;font-size:0.9rem;outline:none;transition:border-color 0.2s;" onfocus="this.style.borderColor='#58a6ff'" onblur="this.style.borderColor='#30363d'">
                </div>
                <button type="submit" style="padding:10px 20px;background:#21262d;color:#f0f6fc;border:1px solid #30363d;border-radius:8px;font-size:0.9rem;font-weight:600;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#30363d'" onmouseout="this.style.background='#21262d'">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.users') }}" style="padding:10px 20px;background:transparent;color:#f85149;border:1px solid rgba(248, 81, 73, 0.3);border-radius:8px;font-size:0.9rem;font-weight:600;text-decoration:none;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(248, 81, 73, 0.1)';this.style.borderColor='#f85149'" onmouseout="this.style.background='transparent';this.style.borderColor='rgba(248, 81, 73, 0.3)'">Clear</a>
                @endif
            </form>
        </div>

        <!-- Users Table -->
        <div style="background:#0d1117;border:1px solid #30363d;border-radius:12px;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;text-align:left;">
                <thead>
                    <tr style="background:#161b22;border-bottom:1px solid #30363d;">
                        <th style="padding:16px 20px;font-size:.85rem;color:#8b949e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">User</th>
                        <th style="padding:16px 20px;font-size:.85rem;color:#8b949e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Email</th>
                        <th style="padding:16px 20px;font-size:.85rem;color:#8b949e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Joined</th>
                        <th style="padding:16px 20px;font-size:.85rem;color:#8b949e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom:1px solid #30363d;transition:background 0.2s;" onmouseover="this.style.background='#161b22'" onmouseout="this.style.background='transparent'">
                            <td style="padding:16px 20px;display:flex;align-items:center;gap:12px;">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                                @else
                                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:.9rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <a href="{{ route('profile.show', $user->id) }}" style="color:#f0f6fc;font-weight:600;text-decoration:none;font-size:0.95rem;">{{ $user->name }}</a>
                                        @if($user->isAdmin())
                                            <span style="font-size:.65rem;background:rgba(88,166,255,0.1);color:#58a6ff;padding:2px 6px;border-radius:4px;font-weight:800;border:1px solid rgba(88,166,255,0.2);">ADMIN</span>
                                        @endif
                                    </div>
                                    <div style="font-size:0.8rem;color:#8b949e;margin-top:2px;">ID: {{ $user->id }}</div>
                                </div>
                            </td>
                            <td style="padding:16px 20px;color:#c9d1d9;font-size:.9rem;">{{ $user->email }}</td>
                            <td style="padding:16px 20px;color:#8b949e;font-size:.9rem;">{{ $user->created_at->format('M d, Y') }}</td>
                            <td style="padding:16px 20px;text-align:right;">
                                @if(!$user->isAdmin())
                                    <button @click="deleteModalOpen = true; deleteUserId = '{{ $user->id }}'; deleteUserName = '{{ addslashes($user->name) }}';" style="background:transparent;border:1px solid rgba(248,81,73,0.3);color:#f85149;padding:6px 14px;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(248,81,73,0.1)';this.style.borderColor='#f85149'" onmouseout="this.style.background='transparent';this.style.borderColor='rgba(248,81,73,0.3)'">Delete User</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:40px 20px;text-align:center;">
                                <div style="color:#8b949e;font-size:1rem;margin-bottom:8px;">No users found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:16px 20px;border-top:1px solid #30363d;background:#0d1117;">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="deleteModalOpen" x-transition x-cloak style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(5,5,10,0.8);backdrop-filter:blur(4px);">
            <div @click.outside="deleteModalOpen = false" style="background:#161b22;border:1px solid #30363d;border-radius:12px;width:100%;max-width:400px;padding:32px;box-shadow:0 20px 40px rgba(0,0,0,0.8);">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:rgba(248,81,73,0.1);display:flex;align-items:center;justify-content:center;color:#f85149;">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size:1.2rem;"></i>
                    </div>
                    <h3 style="font-size:1.3rem;font-weight:700;color:#f0f6fc;margin:0;">Delete User</h3>
                </div>
                
                <p style="color:#8b949e;font-size:.95rem;line-height:1.5;margin-bottom:24px;">Are you sure you want to delete <strong style="color:#f0f6fc;" x-text="deleteUserName"></strong>? This action cannot be undone and will permanently remove their data.</p>
                
                <form :action="'/admin/users/' + deleteUserId" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div style="margin-bottom:24px;">
                        <label style="display:block;color:#c9d1d9;font-size:.85rem;font-weight:600;margin-bottom:8px;">Reason for Deletion (Mailed to user)</label>
                        <textarea name="reason" required rows="3" placeholder="Explain why this account is being deleted..." style="width:100%;background:#0d1117;border:1px solid #30363d;border-radius:8px;padding:12px;color:#c9d1d9;font-size:.9rem;outline:none;resize:vertical;transition:border-color 0.2s;" onfocus="this.style.borderColor='#f85149'" onblur="this.style.borderColor='#30363d'"></textarea>
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:12px;">
                        <button type="button" @click="deleteModalOpen = false" style="padding:10px 20px;background:transparent;border:1px solid #30363d;color:#c9d1d9;border-radius:8px;font-size:0.9rem;font-weight:600;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">Cancel</button>
                        <button type="submit" style="padding:10px 20px;background:#f85149;color:#fff;border:none;border-radius:8px;font-size:0.9rem;font-weight:600;cursor:pointer;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Confirm Delete</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
