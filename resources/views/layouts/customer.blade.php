<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sicasa Cafe - Order</title>
    
    <!-- Load Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Load Alpine.js untuk interaksi tanpa loading -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Midtrans Snap.js (Untuk memunculkan pop-up pembayaran) -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <!-- Google Fonts: Space Grotesk (Heading) & Inter (Body) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F7F7F2; color: #17191C; }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
        
        /* Palet Warna Brand Sicasa (Kalau belum ada di Tailwind config) */
        .bg-brand-lime { background-color: #A8FF00; }
        .text-brand-lime { color: #A8FF00; }
        .bg-brand-blue { background-color: #182B87; }
        .text-brand-blue { color: #182B87; }
        
        /* Hilangkan panah naik-turun pada input number */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="antialiased">
    <!-- Kontainer Pembatas agar di Laptop pun tetap berukuran layar HP -->
    <div class="max-w-md mx-auto bg-white min-h-screen relative overflow-x-hidden shadow-2xl">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>