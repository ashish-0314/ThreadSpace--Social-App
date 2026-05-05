<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900" style="margin:0;padding:0;overflow-x:hidden;">
        <div style="display:flex;min-height:100vh;">
            
            <!-- Left Side (Hero) -->
            <div style="flex:1;background:linear-gradient(135deg, #4f46e5, #0ea5e9, #8b5cf6);background-size:200% 200%;animation:gradientAnimation 10s ease infinite;display:flex;flex-direction:column;justify-content:center;padding:40px;color:white;position:relative;overflow:hidden;" class="hidden md:flex">
                <style>
                    @keyframes gradientAnimation {
                        0% { background-position: 0% 50%; }
                        50% { background-position: 100% 50%; }
                        100% { background-position: 0% 50%; }
                    }
                </style>
                <div style="position:absolute;inset:0;background:url('https://www.transparenttextures.com/patterns/stardust.png');opacity:0.2;"></div>
                
                <div style="position:relative;z-index:10;max-width:500px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
                        <div style="background:white;border-radius:12px;padding:8px;">
                            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#4f46e5" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <h1 style="font-size:2rem;font-weight:800;letter-spacing:-0.5px;">ThreadSpace</h1>
                    </div>
                    
                    <h2 style="font-size:3rem;font-weight:800;line-height:1.1;margin-bottom:16px;">Explore. Share. Unite.</h2>
                    <p style="font-size:1.1rem;opacity:0.9;line-height:1.6;margin-bottom:32px;">Connect with friends, share your moments, and explore a world of stories.</p>
                    
                    <div style="display:flex;flex-direction:column;gap:12px;width:100%;max-width:300px;">
                        <a href="/auth/google" style="display:flex;align-items:center;justify-content:center;gap:10px;background:white;color:#1f2937;padding:12px 24px;border-radius:999px;font-weight:600;text-decoration:none;transition:transform 0.2s;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Sign Up with Google
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side (Form) -->
            <div style="flex:1;display:flex;align-items:center;justify-content:center;background:#f3f4f6;padding:24px;">
                <div style="width:100%;max-width:440px;">
                    {{ $slot }}
                </div>
            </div>
            
        </div>
    </body>
</html>
