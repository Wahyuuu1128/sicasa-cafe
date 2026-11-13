<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicasa Cafe - Bayar Tunai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Library untuk Bikin Barcode via JS -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F7F7F2; }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-white rounded-[2rem] shadow-xl w-full max-w-sm p-8 text-center border border-gray-100">
        
        <h1 class="font-heading text-2xl font-bold text-[#182B87] mb-1">Tunjukkan ke Kasir</h1>
        <p class="text-gray-500 text-sm mb-8 font-medium">Bawa HP kamu ke meja kasir untuk bayar.</p>

        <!-- AREA BARCODE -->
        <div class="bg-gray-50 p-6 rounded-2xl mb-8 border border-gray-200 flex justify-center items-center">
            <svg id="barcode-kasir" class="w-full max-w-[250px]"></svg>
        </div>

        <!-- RINCIAN SINGKAT -->
        <div class="text-left bg-[#F7F7F2] p-5 rounded-2xl mb-8 border border-gray-100">
            <div class="flex justify-between items-center text-sm mb-2">
                <span class="text-gray-500 font-medium">Meja:</span>
                <span class="font-bold text-gray-900">{{ $order->table->nomor_meja ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center text-sm mb-4">
                <span class="text-gray-500 font-medium">Pemesan:</span>
                <span class="font-bold text-gray-900">{{ $order->nama_pemesan }}</span>
            </div>
            <div class="flex justify-between items-end border-t border-gray-200 pt-4">
                <span class="font-bold text-gray-500 text-sm uppercase tracking-wider">Total</span>
                <span class="text-2xl font-bold text-[#182B87]">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('customer.menu') }}" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-heading font-bold text-base py-4 rounded-xl transition-all flex justify-center items-center gap-2">
            Kembali ke Menu
        </a>

    </div>

    <!-- Script Render Barcode -->
    <script>
        // Bikin barcode dari text Order ID
        JsBarcode("#barcode-kasir", "SICASA-{{ $order->id }}", {
            format: "CODE128",
            lineColor: "#17191C",
            width: 2,
            height: 60,
            displayValue: true,
            fontSize: 14,
            fontOptions: "bold",
            background: "transparent"
        });
        
        // Hapus localStorage keranjang karena pesanan udah masuk
        localStorage.removeItem('sicasa_cart');
    </script>
</body>
</html>