<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sicasa Analytics')</title>

    <!-- PWA Settings -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#182B87">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Sicasa POS">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .page-transition { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-paper font-body text-ink min-h-screen pb-12 antialiased">

    <nav class="bg-surface border-b border-gray-200 px-4 md:px-6 py-4 sticky top-0 z-20 shadow-sm">
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            
            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 overflow-hidden rounded-lg bg-surface-muted flex-shrink-0">
                        <img src="{{ asset('images/logo.png') }}" alt="Sicasa Logo" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h1 class="font-heading text-xl font-bold tracking-tight text-ink leading-none">SICASA</h1>
                        <p class="text-[10px] font-semibold text-text-secondary uppercase tracking-widest mt-1">Analytics</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-ink border border-gray-300 rounded-lg px-3 py-2 hover:bg-gray-50 flex items-center gap-2">
                        <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            </div>

            <div class="bg-gray-100 p-1 rounded-xl flex text-sm font-medium w-full md:w-auto overflow-x-auto hide-scrollbar">
                <a href="{{ route('owner.dashboard') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap {{ request()->routeIs('owner.dashboard') ? 'bg-white text-ink shadow-sm font-bold' : 'text-text-secondary hover:text-ink' }}">
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                    Dashboard
                </a>
                <a href="{{ route('owner.history') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap {{ request()->routeIs('owner.history') ? 'bg-white text-ink shadow-sm font-bold' : 'text-text-secondary hover:text-ink' }}">
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Riwayat
                </a>
                <a href="{{ route('profile.edit') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap {{ request()->routeIs('profile.edit') ? 'bg-white text-ink shadow-sm font-bold' : 'text-text-secondary hover:text-ink' }}">
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"></path><path d="M12 14a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"></path><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"></path></svg>
                    Akun
                </a>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-ink border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-50 transition-colors">
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="px-4 md:px-8 max-w-5xl mx-auto mt-8 page-transition">
        @yield('content')
    </main>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA Service Worker registered:', reg.scope))
                    .catch(err => console.error('PWA Service Worker registration error:', err));
            });
        }
    </script>
</body>
</html>