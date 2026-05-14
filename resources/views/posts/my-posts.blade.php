<x-app-layout>
<div style="max-width:800px;margin:0 auto;padding:32px 16px;">

    {{-- Page Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#f0f6fc;margin:0 0 4px;display:flex;align-items:center;gap:8px;"><i class="fa-regular fa-file-lines" style="color:#58a6ff;"></i> My Posts</h1>
            <p style="font-size:.88rem;color:#8b949e;margin:0;">Manage all posts you have created</p>
        </div>
        <a href="{{ route('posts.create.standalone') }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;background:#2f81f7;color:white;font-weight:700;font-size:.88rem;text-decoration:none;transition:background .2s;"
           onmouseover="this.style.background='#1f6feb'" onmouseout="this.style.background='#2f81f7'">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Create New Post
        </a>
    </div>

    {{-- Sort Tabs --}}
    <div style="display:flex;gap:6px;margin-bottom:20px;">
        @foreach(['latest' => '<i class="fa-regular fa-clock"></i> Latest', 'top' => '<i class="fa-solid fa-fire"></i> Top', 'trending' => '<i class="fa-solid fa-arrow-trend-up"></i> Trending'] as $key => $label)
            <a href="{{ route('posts.mine', ['sort' => $key]) }}"
               style="padding:7px 16px;border-radius:20px;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .15s;
                      background: {{ $sort === $key ? '#2f81f7' : '#21262d' }};
                      color: {{ $sort === $key ? 'white' : '#8b949e' }};
                      border: 1px solid {{ $sort === $key ? '#2f81f7' : '#30363d' }};">
                {{ $label }}
            </a>
        @endforeach
    </div>



    {{-- Empty State --}}
    @if($posts->isEmpty())
        <div style="text-align:center;padding:80px 20px;background:#161b22;border:1px solid #30363d;border-radius:16px;">
            <i class="fa-regular fa-envelope-open" style="font-size:2.5rem;color:#30363d;display:block;margin-bottom:16px;"></i>
            <p style="color:#f0f6fc;font-weight:700;font-size:1.1rem;margin-bottom:8px;">No posts yet!</p>
            <p style="color:#8b949e;font-size:.9rem;margin-bottom:24px;">When you create posts, they will appear here.</p>
            <a href="{{ route('posts.create.standalone') }}"
               style="display:inline-flex;align-items:center;gap:6px;padding:12px 24px;border-radius:10px;background:#2f81f7;color:white;font-weight:700;text-decoration:none;">
                Create Your First Post
            </a>
        </div>
    @else
        {{-- Posts List --}}
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($posts as $post)
            <div x-data="{ showDelete: false }" style="background:#161b22;border:1px solid #30363d;border-radius:14px;padding:20px;transition:border-color .2s;"
                 onmouseover="this.style.borderColor='#484f58'" onmouseout="this.style.borderColor='#30363d'">

                {{-- Top row: flair + meta --}}
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;flex-wrap:wrap;">
                    <span style="font-size:.72rem;font-weight:800;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em;
                        background: {{ ['Question'=>'rgba(139,92,246,.15)','Discussion'=>'rgba(59,130,246,.15)','Tutorial'=>'rgba(16,185,129,.15)','Opinion'=>'rgba(245,158,11,.15)'][$post->intent] ?? 'rgba(100,100,100,.15)' }};
                        color: {{ ['Question'=>'#a78bfa','Discussion'=>'#60a5fa','Tutorial'=>'#34d399','Opinion'=>'#fbbf24'][$post->intent] ?? '#8b949e' }};">
                        {{ $post->intent }}
                    </span>
                    @if($post->community)
                        <span style="font-size:.78rem;color:#2f81f7;font-weight:600;">c/{{ $post->community->name }}</span>
                    @else
                        <span style="font-size:.78rem;color:#6b7280;">No community</span>
                    @endif
                    <span style="font-size:.78rem;color:#484f58;">•</span>
                    <span style="font-size:.78rem;color:#6b7280;">{{ $post->created_at->diffForHumans() }}</span>
                    <span style="font-size:.72rem;padding:2px 8px;border-radius:6px;background:#21262d;color:#8b949e;margin-left:auto;">
                        {{ ucfirst($post->type) }}
                    </span>
                </div>

                {{-- Title --}}
                <a href="{{ route('posts.show', $post) }}"
                   style="display:block;font-size:1rem;font-weight:700;color:#f0f6fc;text-decoration:none;line-height:1.45;margin-bottom:12px;"
                   onmouseover="this.style.color='#2f81f7'" onmouseout="this.style.color='#f0f6fc'">
                    {{ $post->title }}
                </a>

                {{-- Stats row --}}
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;font-size:.8rem;color:#6b7280;">
                    <span><i class="fa-solid fa-arrow-up" style="margin-right:3px;"></i>{{ (int)($post->upvotes ?? 0) - (int)($post->downvotes ?? 0) }} votes</span>
                    <span><i class="fa-regular fa-comment" style="margin-right:3px;"></i>{{ $post->comments_count ?? 0 }} comments</span>
                </div>

                {{-- Action buttons --}}
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a href="{{ route('posts.show', $post) }}"
                       style="display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:8px;background:#21262d;border:1px solid #30363d;color:#8b949e;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .15s;"
                       onmouseover="this.style.background='#30363d';this.style.color='#d4d9e0'" onmouseout="this.style.background='#21262d';this.style.color='#8b949e'">
                        <i class="fa-regular fa-eye"></i> View
                    </a>
                    <a href="{{ route('posts.edit', $post) }}"
                       style="display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:8px;background:#21262d;border:1px solid #30363d;color:#2f81f7;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .15s;"
                       onmouseover="this.style.background='#30363d'" onmouseout="this.style.background='#21262d'">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <button @click="showDelete=true"
                            style="display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:8px;background:#21262d;border:1px solid #30363d;color:#f85149;font-size:.8rem;font-weight:600;cursor:pointer;transition:all .15s;"
                            onmouseover="this.style.background='#30363d'" onmouseout="this.style.background='#21262d'">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>

                {{-- Delete Confirmation Modal --}}
                <div x-show="showDelete" x-transition
                     style="position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:999;display:flex;align-items:center;justify-content:center;"
                     @click.self="showDelete=false">
                    <div style="background:#161b22;border:1px solid #30363d;border-radius:16px;padding:32px;max-width:400px;width:90%;text-align:center;">
                        <i class="fa-solid fa-trash" style="font-size:2rem;color:#f85149;display:block;margin-bottom:16px;"></i>
                        <p style="font-size:1.1rem;font-weight:800;color:#f0f6fc;margin-bottom:8px;">Delete this post?</p>
                        <p style="font-size:.88rem;color:#8b949e;margin-bottom:24px;line-height:1.6;">
                            "<strong style="color:#d4d9e0;">{{ Str::limit($post->title, 60) }}</strong>" will be permanently removed along with all its comments.
                        </p>
                        <div style="display:flex;justify-content:center;gap:10px;">
                            <button @click="showDelete=false"
                                    style="padding:10px 22px;border-radius:10px;background:#21262d;border:1px solid #30363d;color:#d4d9e0;font-weight:700;cursor:pointer;font-size:.88rem;">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="padding:10px 22px;border-radius:10px;background:#da3633;border:none;color:white;font-weight:700;cursor:pointer;font-size:.88rem;">
                                    Yes, Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
        <div style="margin-top:24px;display:flex;justify-content:center;">
            {{ $posts->links() }}
        </div>
        @endif
    @endif
</div>
</x-app-layout>
