<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicasa Cafe</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper font-body text-ink min-h-screen antialiased flex flex-col selection:bg-brand-lime selection:text-ink">

    <!-- Navbar Sederhana -->
    <nav class="p-6 md:px-12 flex justify-between items-center bg-surface border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 overflow-hidden rounded-lg bg-brand-blue flex-shrink-0 flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Sicasa Logo" class="w-full h-full object-cover">
            </div>
            <h1 class="font-heading text-xl font-bold tracking-tight">SICASA</h1>
        </div>
        
        <div>
            @auth
                <!-- Jika sudah login, tombol ini mengarah ke Pengatur Lalu Lintas yang kita buat di web.php -->
                <a href="{{ url('/dashboard') }}" class="font-semibold text-sm border-2 border-ink px-5 py-2 hover:bg-brand-lime hover:border-brand-lime transition-colors rounded-sm">
                    Masuk Dasbor
                </a>
            @else
                <a href="{{ route('login') }}" class="font-semibold text-sm border-2 border-ink px-5 py-2 hover:bg-brand-lime hover:border-brand-lime transition-colors rounded-sm">
                    Login Staf
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section Editorial -->
    <main class="flex-grow flex items-center justify-center p-6 md:p-12 text-center">
        <div class="max-w-3xl">
            <h2 class="font-heading text-5xl md:text-7xl font-bold tracking-tighter mb-6 leading-tight">
                Modern Social Space <br> <span class="text-brand-blue">in Padang.</span>
            </h2>
            <p class="text-lg text-text-secondary mb-10 max-w-xl mx-auto">
                Sistem pemesanan QR dan kasir terintegrasi untuk memberikan pengalaman nongkrong yang cepat dan tanpa hambatan.
            </p>
            
            <a href="#" class="inline-block bg-brand-blue text-white font-heading font-bold text-lg px-8 py-4 rounded-sm hover:bg-blue-800 transition-colors shadow-sm">
                Lihat Menu Sicasa
            </a>
        </div>
    </main>

</body>
</html>