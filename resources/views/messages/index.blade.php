<x-app-layout>
<div style="max-width:1000px;margin:0 auto;padding:24px 16px;height:calc(100vh - 80px);">

    <div style="display:flex;height:100%;background:#161b22;border:1px solid #30363d;border-radius:16px;overflow:hidden;">
        
        {{-- Sidebar: List of Connections --}}
        <div style="width:300px;border-right:1px solid #30363d;display:flex;flex-direction:column;background:#0d1117;">
            <div style="padding:20px;border-bottom:1px solid #30363d;">
                <h2 style="font-size:1.2rem;font-weight:800;color:#f0f6fc;margin:0;">💬 Messages</h2>
            </div>
            
            <div style="flex:1;overflow-y:auto;padding:12px;">
                @if($chatUsers->isEmpty())
                    <div style="text-align:center;padding:40px 20px;color:#8b949e;">
                        <p style="font-size:2rem;margin-bottom:8px;">👥</p>
                        <p style="font-size:.9rem;">You don't have any connections yet.</p>
                    </div>
                @else
                    @foreach($chatUsers as $cUser)
                        @php
                            $isActive = $activeUser && $activeUser->id === $cUser->id;
                            // Check for unread messages from this user
                            $unreadCount = \App\Models\Message::where('sender_id', $cUser->id)
                                ->where('receiver_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        <a href="{{ route('messages.index', $cUser->id) }}" 
                           style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:12px;text-decoration:none;transition:all .2s;margin-bottom:4px;
                                  background: {{ $isActive ? '#21262d' : 'transparent' }};
                                  border: 1px solid {{ $isActive ? '#30363d' : 'transparent' }};"
                           onmouseover="this.style.background='#21262d'" onmouseout="this.style.background='{{ $isActive ? '#21262d' : 'transparent' }}'">
                            
                            {{-- Avatar --}}
                            @if($cUser->avatar_url)
                                <img src="{{ $cUser->avatar_url }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                            @else
                                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;color:#fff;">
                                    {{ strtoupper(substr($cUser->name, 0, 1)) }}
                                </div>
                            @endif

                            <div style="flex:1;min-width:0;">
                                <div style="font-size:.95rem;font-weight:700;color:{{ $isActive ? '#f0f6fc' : '#c9d1d9' }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $cUser->name }}
                                </div>
                                <div style="font-size:.8rem;color:#8b949e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    u/{{ $cUser->name }}
                                </div>
                            </div>

                            @if($unreadCount > 0)
                                <div style="background:#f85149;color:white;font-size:.7rem;font-weight:800;padding:2px 8px;border-radius:12px;">
                                    {{ $unreadCount }}
                                </div>
                            @endif
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Main Chat Area --}}
        <div style="flex:1;display:flex;flex-direction:column;background:#161b22;position:relative;">
            @if(!$activeUser)
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#8b949e;">
                    <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="margin-bottom:16px;opacity:0.5;"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p style="font-size:1.2rem;font-weight:600;color:#c9d1d9;">Select a conversation</p>
                    <p style="font-size:.9rem;">Choose a connection from the left to start chatting.</p>
                </div>
            @else
                {{-- Chat Header --}}
                <div style="padding:16px 24px;border-bottom:1px solid #30363d;display:flex;align-items:center;gap:12px;background:#161b22;z-index:10;">
                    @if($activeUser->avatar_url)
                        <img src="{{ $activeUser->avatar_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                    @else
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1a8cd8,#0e9a74);display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:800;color:#fff;">
                            {{ strtoupper(substr($activeUser->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <a href="{{ route('profile.show', $activeUser) }}" style="font-size:1.05rem;font-weight:700;color:#f0f6fc;text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            {{ $activeUser->name }}
                        </a>
                    </div>
                </div>

                {{-- Messages List --}}
                <div id="chat-messages" style="flex:1;overflow-y:auto;padding:24px;display:flex;flex-direction:column;gap:16px;">
                    @if($messages->isEmpty())
                        <div style="text-align:center;padding:40px;color:#8b949e;font-size:.9rem;">
                            This is the beginning of your direct message history with <strong>{{ $activeUser->name }}</strong>.
                        </div>
                    @else
                        @foreach($messages as $msg)
                            @php
                                $isMe = $msg->sender_id === auth()->id();
                            @endphp
                            <div style="display:flex;flex-direction:column;align-items:{{ $isMe ? 'flex-end' : 'flex-start' }};">
                                <div style="max-width:75%;width:fit-content;padding:10px 16px;border-radius:16px;font-size:.95rem;line-height:1.5;white-space:pre-wrap;word-break:break-word;
                                            background: {{ $isMe ? '#2f81f7' : '#21262d' }};
                                            color: {{ $isMe ? 'white' : '#e6edf3' }};
                                            border-bottom-{{ $isMe ? 'right' : 'left' }}-radius: 4px;">{{ $msg->body }}</div>
                                <div style="font-size:.7rem;color:#8b949e;margin-top:6px;margin-{{ $isMe ? 'right' : 'left' }}:4px;">
                                    {{ $msg->created_at->format('M j, g:i A') }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Message Input Form --}}
                <div style="padding:16px 24px;border-top:1px solid #30363d;background:#0d1117;">
                    <form action="{{ route('messages.store') }}" method="POST" style="display:flex;gap:12px;align-items:flex-end;">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeUser->id }}">
                        
                        <div style="flex:1;background:#161b22;border:1px solid #30363d;border-radius:24px;padding:8px 16px;display:flex;align-items:center;">
                            <input type="text" name="body" placeholder="Message {{ $activeUser->name }}..." required autocomplete="off"
                                   style="width:100%;background:transparent;border:none;color:#f0f6fc;font-size:.95rem;outline:none;padding:4px 0;">
                        </div>
                        
                        <button type="submit" style="width:40px;height:40px;border-radius:50%;background:#2f81f7;border:none;color:white;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;transition:all .2s;"
                                onmouseover="this.style.background='#388bfd'" onmouseout="this.style.background='#2f81f7'">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto-scroll to bottom of chat
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-messages');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
</x-app-layout>
