<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - Sicasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 font-sans flex items-center justify-center min-h-screen">
    
    <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md text-center border-t-8 border-orange-500">
        <h1 class="text-2xl font-black text-gray-800 mb-1">Terima Kasih, {{ $order->nama_pemesan }}!</h1>
        <p class="text-gray-500 font-medium mb-6">Pesanan di <span class="font-bold text-gray-700">{{ $order->table->nomor_meja }}</span> telah tercatat.</p>
        
        <!-- Status Indikator -->
        @if($order->status_pesanan == 'menunggu_bayar')
            <div class="mb-6 p-3 rounded-lg bg-yellow-100 text-yellow-800 border border-yellow-200">
                <p class="font-bold text-sm uppercase tracking-wider">Menunggu Verifikasi Kasir</p>
            </div>
        @elseif($order->status_pesanan == 'diproses')
            <div class="mb-6 p-3 rounded-lg bg-blue-100 text-blue-800 border border-blue-200">
                <p class="font-bold text-sm uppercase tracking-wider">Pesanan Diproses Dapur</p>
            </div>
        @else
            <div class="mb-6 p-3 rounded-lg bg-green-100 text-green-800 border border-green-200">
                <p class="font-bold text-sm uppercase tracking-wider">Pesanan Selesai / Lunas</p>
            </div>
        @endif

        <!-- Rincian Harga & Metode -->
        <div class="mb-6">
            <p class="text-sm text-gray-500">Total Tagihan:</p>
            <p class="text-4xl font-black text-gray-800 my-1">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-2">Metode Pembayaran: <span class="uppercase font-bold text-gray-700">{{ $order->payment->metode_pembayaran }}</span></p>
        </div>

        <!-- Instruksi Pembayaran Pay-First -->
        @if($order->status_pesanan == 'menunggu_bayar')
            @if($order->payment->metode_pembayaran == 'qris')
                <div class="mb-6 p-4 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                    <p class="text-sm text-gray-700 font-semibold mb-2">Silakan scan QRIS di meja atau di area kasir.</p>
                    <p class="text-xs text-gray-500">Jika sudah berhasil transfer, tunggu sejenak. Kasir akan segera memverifikasi pesananmu.</p>
                </div>
            @else
                <div class="mb-6 p-4 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                    <p class="text-sm text-gray-700 font-semibold mb-2">Silakan menuju kasir untuk menyerahkan uang tunai.</p>
                    <p class="text-xs text-gray-500">Tunjukkan nama pemesanmu kepada kasir.</p>
                </div>
            @endif
        @endif

        <!-- Daftar Pesanan -->
        <div class="text-left bg-gray-50 p-4 rounded-lg mb-6 border border-gray-100">
            <p class="font-bold text-sm text-gray-700 mb-2 border-b pb-1">Rincian Menu:</p>
            <ul class="text-sm">
                @foreach($order->orderDetails as $detail)
                    <li class="flex justify-between mb-1 text-gray-600">
                        <span>{{ $detail->jumlah }}x {{ $detail->menu->nama_menu }}</span>
                        @if($detail->catatan_kustomisasi)
                            <span class="text-[10px] text-gray-400 italic">({{ $detail->catatan_kustomisasi }})</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        
        <!-- Tombol Refresh (Karena belum pakai WebSocket) -->
        <button onclick="window.location.reload()" class="w-full border-2 border-gray-200 text-gray-600 font-bold rounded-xl py-3 hover:bg-gray-100 transition-colors active:scale-95">
            Muat Ulang Status
        </button>
    </div>

</body>
</html>