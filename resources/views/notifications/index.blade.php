<x-app-layout>
    <div style="max-width:640px;margin:0 auto;padding:32px 16px;">
        <h1 style="font-size:1.4rem;font-weight:800;color:#f0f6fc;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
            <i class="fa-regular fa-bell" style="color:#58a6ff;"></i> Notifications
        </h1>

        @if($notifications->isEmpty())
            <div style="text-align:center;padding:80px 20px;background:#161b22;border:1px solid #30363d;border-radius:16px;">
                <i class="fa-regular fa-bell-slash" style="font-size:2.5rem;color:#30363d;display:block;margin-bottom:16px;"></i>
                <p style="color:#8b949e;font-size:1rem;">You're all caught up!</p>
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($notifications as $notification)
                    @php
                        $icons = [
                            'follow'               => ['icon' => 'fa-solid fa-user-plus',       'color' => '#58a6ff', 'bg' => 'rgba(88,166,255,.12)'],
                            'connection_request'   => ['icon' => 'fa-solid fa-handshake',        'color' => '#a371f7', 'bg' => 'rgba(163,113,247,.12)'],
                            'connection_accepted'  => ['icon' => 'fa-solid fa-circle-check',     'color' => '#3fb950', 'bg' => 'rgba(63,185,80,.12)'],
                            'comment'              => ['icon' => 'fa-regular fa-comment',         'color' => '#ffa657', 'bg' => 'rgba(255,166,87,.12)'],
                            'reply'                => ['icon' => 'fa-solid fa-reply',             'color' => '#f78166', 'bg' => 'rgba(247,129,102,.12)'],
                            'upvote'               => ['icon' => 'fa-solid fa-arrow-up',          'color' => '#3fb950', 'bg' => 'rgba(63,185,80,.12)'],
                            'repost'               => ['icon' => 'fa-solid fa-retweet',           'color' => '#58a6ff', 'bg' => 'rgba(88,166,255,.12)'],
                        ];
                        $style = $icons[$notification->type] ?? ['icon' => 'fa-solid fa-bell', 'color' => '#8b949e', 'bg' => 'rgba(139,148,158,.12)'];
                    @endphp

                    <div style="background:#161b22;border:1px solid {{ $notification->is_read ? '#21262d' : '#2f81f7' }};border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:14px;transition:border-color .2s;">

                        {{-- Icon --}}
                        <div style="width:42px;height:42px;border-radius:50%;background:{{ $style['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="{{ $style['icon'] }}" style="color:{{ $style['color'] }};font-size:1rem;"></i>
                        </div>

                        {{-- Text --}}
                        <div style="flex:1;min-width:0;">
                            <p style="margin:0;color:#d4d9e0;font-size:.88rem;line-height:1.5;">
                                <a href="{{ route('profile.show', $notification->from_user_id) }}" style="font-weight:700;color:#f0f6fc;text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                    {{ $notification->fromUser->name ?? 'Someone' }}
                                </a>
                                @if($notification->type === 'follow')
                                    started following you.
                                @elseif($notification->type === 'connection_request')
                                    sent you a connection request.
                                @elseif($notification->type === 'connection_accepted')
                                    accepted your connection request.
                                @elseif($notification->type === 'comment')
                                    commented on your post.
                                @elseif($notification->type === 'reply')
                                    replied to your comment.
                                @elseif($notification->type === 'upvote')
                                    upvoted your post.
                                @elseif($notification->type === 'repost')
                                    reposted your post.
                                @endif
                                {{-- Link to post if relevant --}}
                                @if($notification->post_id && in_array($notification->type, ['comment','reply','upvote','repost']))
                                    <a href="{{ route('posts.show', $notification->post_id) }}" style="color:#58a6ff;font-weight:600;margin-left:4px;text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">View post →</a>
                                @endif
                            </p>
                            <span style="font-size:.76rem;color:#6b7280;">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>

                        {{-- Accept button for connection requests --}}
                        @if($notification->type === 'connection_request')
                            @php
                                $isStillPending = \App\Models\Connection::where('user_id', $notification->from_user_id)
                                    ->where('connected_user_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->exists();
                            @endphp
                            @if($isStillPending)
                                <form method="POST" action="{{ route('network.accept', $notification->from_user_id) }}">
                                    @csrf
                                    <button type="submit" style="padding:6px 16px;border-radius:20px;background:#238636;color:white;font-weight:700;font-size:.78rem;cursor:pointer;border:none;white-space:nowrap;">
                                        <i class="fa-solid fa-check" style="margin-right:4px;"></i>Accept
                                    </button>
                                </form>
                            @else
                                <span style="font-size:.75rem;color:#3fb950;font-weight:600;"><i class="fa-solid fa-circle-check"></i> Accepted</span>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
            <div style="margin-top:24px;">{{ $notifications->links() }}</div>
        @endif
    </div>
</x-app-layout>