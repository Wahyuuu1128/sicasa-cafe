<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicasa Cafe - Bayar QRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F7F7F2; }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-white rounded-[2rem] shadow-xl w-full max-w-sm p-8 text-center border border-gray-100 relative overflow-hidden">
        
        <!-- Ornamen -->
        <div class="absolute top-[-30px] right-[-30px] w-24 h-24 bg-blue-50 rounded-full z-0"></div>

        <div class="relative z-10">
            <h1 class="font-heading text-2xl font-bold text-[#182B87] mb-1">Bayar Pesanan</h1>
            <p class="text-gray-500 text-sm mb-6 font-medium">Order #SICASA-{{ $order->id }}</p>

            <!-- KOTAK QRIS -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 inline-block mb-6 relative">
                <div class="absolute -top-3 -right-3 bg-[#A8FF00] text-[#182B87] text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                    QRIS
                </div>
                <!-- GAMBAR QR MENTAH DARI MIDTRANS CORE API -->
                <img src="{{ $qr_url }}" alt="QRIS Sicasa" class="w-48 h-48 mx-auto mix-blend-multiply object-contain">
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mb-2 tracking-tight">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</h2>
            <p class="text-xs text-gray-400 mb-8 px-4 leading-relaxed">
                Buka aplikasi e-wallet (GoPay, OVO, Dana) atau M-Banking, lalu scan QR di atas.
            </p>

            <!-- Tombol Cek Status Manual -->
            <button onclick="manualCheck()" class="w-full bg-[#182B87] hover:bg-blue-900 text-white font-heading font-bold text-base py-4 rounded-xl transition-all shadow-md flex justify-center items-center gap-2">
                Saya Sudah Bayar
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </button>
        </div>
    </div>

    <!-- Script Auto-Check Status dari Database -->
    <script>
        // Cek status pesanan setiap 3 detik (Auto-redirect ke nota jika lunas)
        setInterval(() => {
            fetch(`/qr/check-status/{{ $order->id }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'diproses' || data.status === 'lunas') {
                        window.location.href = `/qr/nota/{{ $order->id }}`;
                    }
                })
                .catch(err => console.error("Checking status...", err));
        }, 3000); 

        // Fungsi manual check jika pelanggan memaksa klik tombol
        function manualCheck() {
            fetch(`/qr/check-status/{{ $order->id }}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'diproses' || data.status === 'lunas') {
                        window.location.href = `/qr/nota/{{ $order->id }}`;
                    } else {
                        alert("Pembayaran belum diterima sistem. Silakan tunggu beberapa saat atau pastikan saldo E-Wallet kamu sudah terpotong.");
                    }
                })
                .catch(err => {
                    alert("Gagal mengecek status ke server.");
                });
        }
    </script>
</body>
</html>