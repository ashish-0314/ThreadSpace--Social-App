<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="ThreadSpace — A community-driven platform to connect, share, and discover what matters.">

        <title>{{ config('app.name', 'ThreadSpace') }}</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- ThreadSpace Styles -->
        <link rel="stylesheet" href="{{ asset('css/threadspace.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
                overflow: hidden;
                font-family: 'Inter', sans-serif;
                /* fallback in case CSS file loads late */
                background: #050816 url('{{ asset('images/thbg2.png') }}') no-repeat center center;
                background-size: cover;
            }
        </style>
    </head>
    <body>
        <div class="auth-split-container">

            <!-- Left Side (Hero) — md+ screens only -->
            <div class="auth-left hidden md:flex">
                <div class="auth-content-wrapper">

                    <!-- Logo -->
                    <div class="auth-logo">
                        <div class="auth-logo-icon">
                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <span>ThreadSpace</span>
                    </div>

                    <!-- Headline -->
                    <h1 class="auth-title">
                        <span>Your space.</span>
                        <span>Your people.</span>
                        <span class="highlight">Your stories.</span>
                    </h1>

                    <p class="auth-description">
                        A community-driven platform<br>to connect, share, and discover<br>what matters.
                    </p>

                    <!-- Feature list -->
                    <div class="auth-features">
                        <div class="feature-item">
                            <div class="feature-icon" style="color: #a855f7;"><i class="fa-solid fa-users"></i></div>
                            <div class="feature-text">
                                <h4>Connect</h4>
                                <p>with real people</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon" style="color: #3b82f6;"><i class="fa-regular fa-comment"></i></div>
                            <div class="feature-text">
                                <h4>Share</h4>
                                <p>your moments</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon" style="color: #22c55e;"><i class="fa-regular fa-compass"></i></div>
                            <div class="feature-text">
                                <h4>Explore</h4>
                                <p>new perspectives</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial -->
                    <div class="auth-testimonial">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80" alt="User avatar" class="testimonial-avatar">
                        <div class="testimonial-quote">
                            <i class="fa-solid fa-quote-left"></i>
                            ThreadSpace is where conversations turn into connections.
                        </div>
                    </div>
                </div>

                <!-- Footer copyright -->
                <div style="font-size:0.78rem;color:rgba(255,255,255,0.28);position:relative;z-index:10;">
                    &copy; {{ date('Y') }} ThreadSpace Inc. All rights reserved.
                </div>
            </div>

            <!-- Right Side (Form Panel) -->
            <div class="auth-right">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>
