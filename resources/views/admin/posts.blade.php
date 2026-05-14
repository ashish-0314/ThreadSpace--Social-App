<x-app-layout>
    @section('title', 'Admin Posts')

    <div style="max-width:1200px;margin:40px auto;padding:0 20px;" x-data="{ deleteModalOpen: false, deletePostId: null, deletePostTitle: '' }">
        
        <!-- Header & Nav -->
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:32px;border-bottom:1px solid #30363d;padding-bottom:20px;flex-wrap:wrap;gap:16px;">
            <div>
                <h1 style="font-size:2rem;font-weight:700;color:#f0f6fc;margin:0 0 8px;letter-spacing:-0.5px;">Posts Management</h1>
                <p style="color:#8b949e;font-size:0.95rem;margin:0;">Moderate and manage content across communities.</p>
            </div>
            <div style="display:flex;gap:12px;">
                <a href="{{ route('admin.dashboard') }}" style="padding:8px 16px;background:transparent;border:1px solid #30363d;color:#c9d1d9;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">Overview</a>
                <a href="{{ route('admin.users') }}" style="padding:8px 16px;background:transparent;border:1px solid #30363d;color:#c9d1d9;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='transparent'">Users</a>
                <a href="{{ route('admin.posts') }}" style="padding:8px 16px;background:#58a6ff;color:#0d1117;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;">Posts</a>
            </div>
        </div>

        <!-- Search Bar -->
        <div style="margin-bottom:24px;display:flex;justify-content:flex-end;">
            <form action="{{ route('admin.posts') }}" method="GET" style="display:flex;gap:10px;width:100%;max-width:400px;">
                <div style="position:relative;flex:1;">
                    <i class="fa-solid fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#8b949e;font-size:0.9rem;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, author, etc..." style="width:100%;background:#0d1117;border:1px solid #30363d;border-radius:8px;padding:10px 14px 10px 40px;color:#c9d1d9;font-size:0.9rem;outline:none;transition:border-color 0.2s;" onfocus="this.style.borderColor='#58a6ff'" onblur="this.style.borderColor='#30363d'">
                </div>
                <button type="submit" style="padding:10px 20px;background:#21262d;color:#f0f6fc;border:1px solid #30363d;border-radius:8px;font-size:0.9rem;font-weight:600;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#30363d'" onmouseout="this.style.background='#21262d'">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.posts') }}" style="padding:10px 20px;background:transparent;color:#f85149;border:1px solid rgba(248, 81, 73, 0.3);border-radius:8px;font-size:0.9rem;font-weight:600;text-decoration:none;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(248, 81, 73, 0.1)';this.style.borderColor='#f85149'" onmouseout="this.style.background='transparent';this.style.borderColor='rgba(248, 81, 73, 0.3)'">Clear</a>
                @endif
            </form>
        </div>

        <!-- Posts Table -->
        <div style="background:#0d1117;border:1px solid #30363d;border-radius:12px;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;text-align:left;">
                <thead>
                    <tr style="background:#161b22;border-bottom:1px solid #30363d;">
                        <th style="padding:16px 20px;font-size:.85rem;color:#8b949e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Post Info</th>
                        <th style="padding:16px 20px;font-size:.85rem;color:#8b949e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Community</th>
                        <th style="padding:16px 20px;font-size:.85rem;color:#8b949e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Date</th>
                        <th style="padding:16px 20px;font-size:.85rem;color:#8b949e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr style="border-bottom:1px solid #30363d;transition:background 0.2s;" onmouseover="this.style.background='#161b22'" onmouseout="this.style.background='transparent'">
                            <td style="padding:16px 20px;">
                                <a href="{{ route('posts.show', $post) }}" style="color:#f0f6fc;font-weight:600;display:block;max-width:400px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.95rem;text-decoration:none;margin-bottom:4px;">{{ $post->title ?: ($post->is_repost ? 'Repost' : 'Untitled') }}</a>
                                <div style="font-size:0.8rem;color:#8b949e;display:flex;align-items:center;gap:6px;">
                                    by 
                                    @if($post->user)
                                        <a href="{{ route('profile.show', $post->user->id) }}" style="color:#58a6ff;text-decoration:none;font-weight:500;">u/{{ $post->user->name }}</a>
                                    @else
                                        Unknown
                                    @endif
                                </div>
                            </td>
                            <td style="padding:16px 20px;">
                                @if($post->community)
                                    <a href="{{ route('communities.show', $post->community->slug) }}" style="color:#a371f7;background:rgba(163,113,247,0.1);padding:4px 8px;border-radius:6px;font-size:0.8rem;font-weight:600;text-decoration:none;display:inline-block;">c/{{ $post->community->name }}</a>
                                @else
                                    <span style="color:#8b949e;font-size:0.85rem;">-</span>
                                @endif
                            </td>
                            <td style="padding:16px 20px;color:#8b949e;font-size:.9rem;">{{ $post->created_at->format('M d, Y') }}</td>
                            <td style="padding:16px 20px;text-align:right;">
                                <button @click="deleteModalOpen = true; deletePostId = '{{ $post->id }}'; deletePostTitle = '{{ addslashes($post->title ?: 'This post') }}';" style="background:transparent;border:1px solid rgba(248,81,73,0.3);color:#f85149;padding:6px 14px;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(248,81,73,0.1)';this.style.borderColor='#f85149'" onmouseout="this.style.background='transparent';this.style.borderColor='rgba(248,81,73,0.3)'">Delete Post</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:40px 20px;text-align:center;">
                                <div style="color:#8b949e;font-size:1rem;margin-bottom:8px;">No posts found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:16px 20px;border-top:1px solid #30363d;background:#0d1117;">
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="deleteModalOpen" x-transition x-cloak style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(5,5,10,0.8);backdrop-filter:blur(4px);">
            <div @click.outside="deleteModalOpen = false" style="background:#161b22;border:1px solid #30363d;border-radius:12px;width:100%;max-width:400px;padding:32px;box-shadow:0 20px 40px rgba(0,0,0,0.8);">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:rgba(248,81,73,0.1);display:flex;align-items:center;justify-content:center;color:#f85149;">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size:1.2rem;"></i>
                    </div>
                    <h3 style="font-size:1.3rem;font-weight:700;color:#f0f6fc;margin:0;">Delete Post</h3>
                </div>
                
                <p style="color:#8b949e;font-size:.95rem;line-height:1.5;margin-bottom:24px;">Are you sure you want to delete <strong style="color:#f0f6fc;" x-text="deletePostTitle"></strong>? This action cannot be undone and will permanently remove this content.</p>
                
                <form :action="'/admin/posts/' + deletePostId" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div style="margin-bottom:24px;">
                        <label style="display:block;color:#c9d1d9;font-size:.85rem;font-weight:600;margin-bottom:8px;">Reason for Deletion (Mailed to author)</label>
                        <textarea name="reason" required rows="3" placeholder="Explain why this post is being removed..." style="width:100%;background:#0d1117;border:1px solid #30363d;border-radius:8px;padding:12px;color:#c9d1d9;font-size:.9rem;outline:none;resize:vertical;transition:border-color 0.2s;" onfocus="this.style.borderColor='#f85149'" onblur="this.style.borderColor='#30363d'"></textarea>
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
