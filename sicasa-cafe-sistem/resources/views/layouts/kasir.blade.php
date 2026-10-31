<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sicasa POS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .page-transition { animation: fadeIn 0.2s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        #sidebar { transition: transform 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-paper font-body text-ink h-screen overflow-hidden flex antialiased">

    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden backdrop-blur-sm" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR: Swiss Utility (Bersih, tanpa border tebal) -->
    <aside id="sidebar" class="bg-brand-blue text-white w-64 flex-shrink-0 flex flex-col h-full fixed md:relative z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        
        <div class="h-16 flex items-center gap-3 px-6 border-b border-white/10">
            <div class="w-8 h-8 rounded-lg bg-surface flex items-center justify-center p-1">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-cover rounded-md">
            </div>
            <div>
                <h1 class="font-heading text-lg font-bold tracking-wide leading-none">SICASA</h1>
                <p class="text-[9px] font-semibold text-brand-lime uppercase tracking-widest mt-1">Sistem Kasir</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1 hide-scrollbar">
            
            <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('kasir.dashboard') ? 'bg-white/10 text-brand-lime' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"></rect><rect x="14" y="3" width="7" height="5" rx="1"></rect><rect x="14" y="12" width="7" height="9" rx="1"></rect><rect x="3" y="16" width="7" height="5" rx="1"></rect></svg>
                Dashboard
            </a>

            <a href="{{ route('kasir.menu') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('kasir.menu') ? 'bg-white/10 text-brand-lime' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path></svg>
                Manajemen Menu
            </a>

            <a href="{{ route('kasir.pesanan') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('kasir.pesanan') ? 'bg-brand-lime text-ink font-bold shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                    Pesanan Masuk
                </div>
            </a>

            <a href="{{ route('kasir.pos') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('kasir.pos') ? 'bg-brand-lime text-ink font-bold shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                Point of Sales
            </a>

            <a href="{{ route('kasir.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('kasir.history') ? 'bg-white/10 text-brand-lime' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Riwayat Transaksi
            </a>

            <a href="{{ route('kasir.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-all {{ request()->routeIs('kasir.profile') ? 'bg-white/10 text-brand-lime' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Profil Staf
            </a>
            
        </nav>

        <div class="p-4 border-t border-white/10 bg-black/10">
            <div class="flex items-center gap-3 mb-4 px-1">
                <div class="w-8 h-8 rounded-full bg-brand-lime text-ink flex items-center justify-center font-bold text-sm font-heading">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-white/60 uppercase tracking-wider">Sedang Bertugas</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-white/70 hover:text-red-400 hover:bg-red-400/10 rounded-lg px-4 py-2 text-xs font-semibold flex items-center justify-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Akhiri Shift
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F4F5F7]">
        
        <header class="md:hidden bg-surface border-b border-gray-200 h-14 flex items-center justify-between px-4 flex-shrink-0 z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="p-1.5 -ml-1.5 rounded-md text-ink hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <h1 class="font-heading font-semibold text-base">@yield('title')</h1>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto page-transition p-4 md:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.toggle('hidden');
        }
    </script>
</body>
</html>