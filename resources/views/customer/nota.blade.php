<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicasa Cafe - Nota Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap');
        body { background-color: #F7F7F2; font-family: sans-serif; }
        .receipt-font { font-family: 'Space Mono', monospace; }
        /* Efek gerigi khas struk kasir */
        .receipt-edge { 
            background: radial-gradient(circle, transparent, transparent 4px, white 4px, white);
            background-size: 16px 16px;
            background-position: -8px -8px;
            height: 8px; width: 100%;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Notifikasi Sukses -->
    <div class="mb-6 flex flex-col items-center">
        <div class="w-16 h-16 bg-[#A8FF00] rounded-full flex items-center justify-center mb-3 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#182B87" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <h2 class="text-[#182B87] font-bold text-xl">Pembayaran Berhasil!</h2>
        <p class="text-gray-500 text-sm mt-1">Pesanan kamu sedang disiapkan.</p>
    </div>

    <!-- AREA NOTA (Yang akan dijadikan gambar) -->
    <div id="receipt-area" class="w-full max-w-sm bg-white shadow-xl pt-8 pb-4 px-6 relative mb-6">
        
        <div class="text-center border-b-2 border-dashed border-gray-300 pb-6 mb-6">
            <h1 class="font-bold text-2xl text-gray-900 tracking-tight">SICASA CAFE</h1>
            <p class="text-gray-500 text-xs mt-1">Jln. Raya Sicasa No. 1, Padang</p>
            <p class="text-gray-500 text-xs">IG: @sicasa.cafe</p>
        </div>

        <div class="receipt-font text-xs text-gray-600 mb-6 space-y-1">
            <div class="flex justify-between"><span>Order ID:</span> <span class="font-bold text-gray-900">#{{ $order->id }}</span></div>
            <div class="flex justify-between"><span>Tanggal:</span> <span class="font-bold text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="flex justify-between"><span>Meja:</span> <span class="font-bold text-gray-900">{{ $order->table->nomor_meja ?? '-' }}</span></div>
            <div class="flex justify-between"><span>Pelanggan:</span> <span class="font-bold text-gray-900">{{ $order->nama_pemesan }}</span></div>
            <div class="flex justify-between"><span>Metode:</span> <span class="font-bold text-gray-900 uppercase">{{ $order->payment }}</span></div>
        </div>

        <div class="receipt-font text-sm border-b-2 border-dashed border-gray-300 pb-4 mb-4 space-y-3">
            @foreach($order->orderDetails as $detail)
                <div class="flex justify-between">
                    <div>
                        <p class="font-bold text-gray-900">{{ $detail->menu->nama_menu }}</p>
                        <p class="text-xs text-gray-500">{{ $detail->jumlah }} x Rp{{ number_format($detail->harga, 0, ',', '.') }}</p>
                    </div>
                    <span class="font-bold text-gray-900">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center text-lg font-bold text-gray-900 mb-6">
            <span>TOTAL</span>
            <span>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>

        <div class="text-center text-xs text-gray-500 receipt-font pt-2">
            *** LUNAS ***<br>Terima kasih atas kunjungannya!
        </div>
        
        <!-- Efek Gerigi Kertas Bawah -->
        <div class="absolute bottom-[-4px] left-0 right-0 receipt-edge"></div>
    </div>

    <!-- Tombol Aksi -->
    <div class="w-full max-w-sm flex gap-3">
        <button onclick="downloadReceipt()" class="flex-1 bg-[#182B87] hover:bg-blue-900 text-white font-bold py-3.5 rounded-xl shadow-md flex justify-center items-center gap-2 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Simpan Struk
        </button>
        <a href="{{ route('customer.landing') }}" class="w-14 h-14 bg-white border border-gray-200 text-gray-600 rounded-xl flex items-center justify-center hover:bg-gray-50 transition-all shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="12" x2="9" y2="6"></line><line x1="3" y1="12" x2="9" y2="18"></line></svg>
        </a>
    </div>

    <script>
        function downloadReceipt() {
            const receiptArea = document.getElementById('receipt-area');
            html2canvas(receiptArea, { scale: 2, backgroundColor: "#ffffff" }).then(canvas => {
                let link = document.createElement('a');
                link.download = 'Struk-Sicasa-{{ $order->id }}.png';
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        }
    </script>

</body>
</html>