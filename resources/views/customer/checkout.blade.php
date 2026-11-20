<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicasa Cafe - Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F7F7F2; }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="pb-32">

    <!-- Header -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-50 px-5 py-4 flex items-center justify-between">
        <a href="{{ route('customer.menu') }}" class="text-gray-500 hover:text-[#182B87]">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
        <h1 class="font-heading font-bold text-lg text-[#182B87]">Selesaikan Pesanan</h1>
        <div class="w-6"></div>
    </div>

    <div class="max-w-lg mx-auto p-5 space-y-6">
        
        <!-- ==========================================
             KOTAK NOTIFIKASI ERROR (WAJIB ADA)
             ========================================== -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl shadow-sm">
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl shadow-sm">
                <ul class="text-sm font-bold text-red-800 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('customer.pay') }}" method="POST" id="checkoutForm">
            @csrf
            
            <!-- Hidden Input untuk Kirim Data Keranjang dari Javascript -->
            <input type="hidden" name="cart_data" id="cart_data_input">
            
            <!-- 1. EDIT NAMA & MEJA -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Informasi Pemesan
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor Meja</label>
                        <input type="number" name="nomor_meja" value="{{ $nomor_meja }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#182B87] block p-3 font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Pemesan</label>
                        <input type="text" name="nama_pemesan" value="{{ $nama_pemesan }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#182B87] block p-3 font-bold">
                    </div>
                </div>
                <p class="text-[11px] text-gray-400 mt-2">*Kamu bisa ubah data ini jika ada kesalahan.</p>
            </div>

            <!-- 2. RINGKASAN PESANAN -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm mt-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    Ringkasan Pesanan
                </h3>
                
                <!-- List Keranjang (Diisi oleh Javascript) -->
                <div id="order-items" class="space-y-3 mb-4 border-b border-gray-100 pb-4">
                    <!-- Item dimuat via JS -->
                </div>

                <div class="flex justify-between items-center font-bold text-lg">
                    <span class="text-gray-900">Total Tagihan</span>
                    <span class="text-[#182B87]" id="total-price">Rp0</span>
                </div>
            </div>

            <!-- 3. PILIH METODE PEMBAYARAN -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm mt-6 mb-8">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                    Pilih Metode Pembayaran
                </h3>
                
                <div class="space-y-3">
                    <!-- Radio QRIS -->
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="qris" required class="w-5 h-5 text-[#182B87] focus:ring-[#182B87]">
                            <span class="font-bold text-gray-900">QRIS (Gopay, OVO, Dana, M-Banking)</span>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-800 font-bold px-2 py-1 rounded">Instan</span>
                    </label>

                    <!-- Radio Cash -->
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="cash" required class="w-5 h-5 text-[#182B87] focus:ring-[#182B87]">
                            <span class="font-bold text-gray-900">Bayar Tunai di Kasir</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- TOMBOL BAYAR STICKY -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 z-50">
                <div class="max-w-lg mx-auto">
                    <button type="submit" class="w-full bg-[#A8FF00] hover:bg-[#95e600] text-[#182B87] font-heading font-bold text-lg py-4 rounded-xl shadow-lg transition-all flex justify-center items-center gap-2">
                        Bayar Pesanan
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Script Render Keranjang -->
    <script>
        // Ambil data keranjang dari localStorage (disimpan saat pelanggan klik tambah di halaman menu)
        let cart = JSON.parse(localStorage.getItem('sicasa_cart')) || [];
        
        // Render keranjang ke HTML
        const orderItemsContainer = document.getElementById('order-items');
        const totalPriceEl = document.getElementById('total-price');
        const cartInput = document.getElementById('cart_data_input');
        
        let total = 0;
        
        if (cart.length === 0) {
            orderItemsContainer.innerHTML = '<p class="text-sm text-gray-400 text-center italic">Keranjang kosong</p>';
        } else {
            cart.forEach(item => {
                let subtotal = item.qty * item.harga;
                total += subtotal;
                
                orderItemsContainer.innerHTML += `
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex gap-2">
                            <span class="font-bold text-[#182B87]">${item.qty}x</span>
                            <span class="font-medium text-gray-800">${item.nama}</span>
                        </div>
                        <span class="font-bold text-gray-600">Rp${subtotal.toLocaleString('id-ID')}</span>
                    </div>
                `;
            });
        }
        
        totalPriceEl.innerText = `Rp${total.toLocaleString('id-ID')}`;
        
        // Simpan JSON keranjang ke input hidden biar bisa dibaca Laravel pas form disubmit
        cartInput.value = JSON.stringify(cart);

        // Hentikan submit kalau keranjang kosong
        document.getElementById('checkoutForm').addEventListener('submit', function(e){
            if(cart.length === 0){
                e.preventDefault();
                alert("Keranjang kamu masih kosong!");
            }
        });
    </script>
</body>
</html>