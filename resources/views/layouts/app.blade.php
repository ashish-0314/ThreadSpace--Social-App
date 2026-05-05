<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ThreadSpace') }} - @yield('title', 'Home')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- ThreadSpace Custom Styles -->
        <link rel="stylesheet" href="{{ asset('css/threadspace.css') }}">
    </head>
    <body class="font-sans antialiased" style="background:#111827;">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>

<script>
// ── Global Alpine.js vote widget ─────────────────────────────
// Usage: x-data="voteWidget('postId', 'Post', currentUserVote, currentScore)"
function voteWidget(votableId, votableType, initialVote, initialScore) {
    return {
        userVote: initialVote,   // 1, -1, or null
        score: initialScore,

        get pillClass() {
            if (this.userVote === 1)  return 'voted-up';
            if (this.userVote === -1) return 'voted-down';
            return '';
        },

        async castVote(value) {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const prev = this.userVote;

            // Optimistic UI: apply immediately
            if (prev === value) {
                // Toggle off
                this.score   -= value;
                this.userVote = null;
            } else {
                // New or switched vote
                this.score   += value - (prev || 0);
                this.userVote = value;
            }

            try {
                const res = await fetch('/vote', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        votable_id:   votableId,
                        votable_type: votableType,
                        value:        String(value),
                    }),
                });
                const data = await res.json();
                // Sync with server truth
                this.score    = data.score;
                this.userVote = data.userVote;
            } catch (e) {
                // Revert on network error
                this.score    = initialScore;
                this.userVote = prev;
            }
        }
    }
}
</script>
</html>
