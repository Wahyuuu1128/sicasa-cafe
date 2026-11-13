<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicasa Social Space - Login</title>
    
    <!-- PWA Settings -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#182B87">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Sicasa POS">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@600;700&display=swap');
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased text-[#17191C] bg-[#F0F1EA] h-screen w-full flex items-center justify-center p-4 overflow-hidden">

    <div class="bg-white w-full max-w-6xl h-[90vh] md:h-[580px] rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        <!-- Bagian Kiri: Form Login -->
        <div class="w-full md:w-1/2 px-8 py-8 md:px-14 flex flex-col justify-center h-full overflow-y-auto no-scrollbar">
            
            <!-- Logo & Nama Sicasa (Sejajar Horizontal & Lebih Besar) -->
            <div class="mb-10 flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="SICASA Logo" class="h-14 object-contain">
                <span class="text-[11px] font-bold text-[#182B87] uppercase tracking-[0.2em] bg-[#F0F1EA] px-4 py-2 rounded-full">
                    Sicasa Social Space
                </span>
            </div>

            <!-- Form Header -->
            <h1 class="font-heading text-3xl md:text-4xl font-bold text-[#182B87] mb-2 tracking-tight">Selamat Datang</h1>
            <p class="text-gray-500 text-sm mb-6 leading-relaxed">Silakan masuk menggunakan kredensial staf atau pemilik yang valid.</p>

            @if ($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form Input -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-[11px] font-bold text-gray-700 mb-2 uppercase tracking-wider">Email / Username</label>
                    <input type="email" id="email" name="email" required placeholder="admin@sicasa.id"
                        class="w-full px-4 py-3.5 bg-[#F0F1EA] border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#182B87] focus:border-transparent transition-all text-sm">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider">Password</label>
                        <a href="#" class="text-[11px] font-bold text-[#182B87] hover:underline tracking-wide">Lupa Password?</a>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="w-full px-4 py-3.5 bg-[#F0F1EA] border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#182B87] focus:border-transparent transition-all text-sm">
                </div>

                <div class="flex items-center pt-1 pb-3">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-[#182B87] bg-[#F0F1EA] border-gray-300 rounded focus:ring-[#182B87]">
                    <label for="remember" class="ml-2 text-sm text-gray-600 font-medium">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="w-full bg-[#182B87] hover:bg-blue-900 text-white font-heading font-bold text-lg py-4 rounded-xl shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

        <!-- Bagian Kanan: Foto Cafe -->
        <div class="hidden md:block w-full md:w-1/2 relative bg-[#17191C] h-full">
            <!-- Overlay Multi-layer: Tipis di atas, Gradasi gelap pekat di bawah untuk teks -->
            <div class="absolute inset-0 bg-[#182B87]/20 mix-blend-multiply z-10"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#101B54] via-[#101B54]/40 to-transparent z-10"></div>
            
            <img src="{{ asset('images/sicasa.png') }}" alt="Suasana Sicasa Cafe" class="absolute inset-0 w-full h-full object-cover">
            
            <!-- Teks Slogan Saja (Tanpa Background Box) -->
            <div class="absolute bottom-12 left-10 right-10 z-20">
                <!-- Pakai warna brand-lime biar kontras dan menarik -->
                <h2 class="font-heading font-bold text-[32px] text-[#A8FF00] mb-3 leading-[1.1] tracking-tight">
                    Less crowd.<br>More comfort.<br>More you.
                </h2>
                <!-- Pakai warna paper/putih redup untuk deskripsi -->
                <p class="text-[15px] text-[#F7F7F2] font-medium leading-relaxed max-w-[85%] opacity-90">
                    A cozy corner, a good cup, and enough space to just be.
                </p>
            </div>
            
            <!-- Shape Dekoratif Lime -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-[#A8FF00] rounded-bl-[4rem] opacity-90 z-20 shadow-xl"></div>
        </div>

    </div>

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