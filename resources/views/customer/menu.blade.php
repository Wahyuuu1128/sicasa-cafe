<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicasa Cafe - Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F7F7F2; }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
        
        /* Sembunyikan scrollbar tapi tetap bisa di-scroll */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="pb-28">

    <!-- Container Utama (Ukuran Mobile) -->
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-sm relative border-x border-gray-100">
        
        <!-- ==========================================
             STICKY HEADER & KATEGORI
             ========================================== -->
        <div class="sticky top-0 z-40 bg-white border-b border-gray-100 shadow-sm">
            <!-- Header Info Pemesan -->
            <div class="px-5 py-4 flex justify-between items-center border-b border-gray-50">
                <div>
                    <h2 class="font-heading font-bold text-[#182B87] text-lg">Meja {{ $table->nomor_meja ?? session('nomor_meja') }}</h2>
                    <p class="text-xs text-gray-500 font-medium">Hai, <span class="text-gray-900 font-bold">{{ $nama_pemesan }}</span> 👋</p>
                </div>
                <a href="{{ route('customer.landing') }}" class="bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-500 p-2.5 rounded-full transition-colors shadow-sm" title="Edit Data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>
            </div>

            <!-- Filter Kategori (Horizontal Scroll) -->
            <div class="flex overflow-x-auto gap-3 px-5 py-3 no-scrollbar">
                <button onclick="filterMenu('all')" id="btn-cat-all" class="cat-btn whitespace-nowrap px-5 py-2 rounded-full font-bold text-sm bg-[#182B87] text-white shadow-sm transition-colors">
                    Semua
                </button>
                @foreach($categories as $cat)
                    <button onclick="filterMenu('{{ $cat->id }}')" id="btn-cat-{{ $cat->id }}" class="cat-btn whitespace-nowrap px-5 py-2 rounded-full font-bold text-sm bg-[#F0F1EA] text-gray-600 hover:bg-gray-200 transition-colors">
                        {{ $cat->nama_kategori }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- ==========================================
             GRID MENU (2 KOLOM)
             ========================================== -->
        <div class="p-5 grid grid-cols-2 gap-4">
            @foreach($menus as $menu)
                <!-- Kotak Menu -->
                <!-- Seluruh kotak bisa diklik buat nambah ke keranjang (kecuali tombol minus) -->
                <div class="menu-card flex flex-col bg-white border border-gray-100 shadow-sm hover:shadow-md rounded-2xl overflow-hidden cursor-pointer transition-all relative"
                     data-category="{{ $menu->category_id }}"
                     onclick="updateCart('{{ $menu->id }}', '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, 1)">
                    
                    <!-- Foto Menu (Aspect Square) -->
                    <div class="aspect-square bg-gray-50 relative">
                        @if($menu->foto)
                            <img src="{{ asset('storage/'.$menu->foto) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            </div>
                        @endif
                        
                        <!-- Badge Jumlah (Muncul kalau ada di keranjang) -->
                        <div id="badge-{{ $menu->id }}" class="hidden absolute top-2 right-2 bg-[#A8FF00] text-[#182B87] w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs shadow-md border border-white/50">0</div>
                    </div>
                    
                    <!-- Info & Kontrol -->
                    <div class="p-3 flex flex-col justify-between flex-1">
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm leading-tight mb-1 line-clamp-2">{{ $menu->nama_menu }}</h3>
                            <p class="text-[#182B87] font-bold text-xs">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                        </div>
                        
                        <!-- Kontrol Plus Minus -->
                        <!-- event.stopPropagation() biar kalau klik tombol '-', nggak kepicu fungsi klik kotaknya (+) -->
                        <div class="mt-3 flex items-center justify-between bg-gray-50 rounded-full p-1 border border-gray-100">
                            <button onclick="event.stopPropagation(); updateCart('{{ $menu->id }}', '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, -1)" 
                                class="w-7 h-7 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-600 hover:bg-red-100 hover:text-red-600 font-bold transition-colors">-</button>
                            
                            <span id="qty-{{ $menu->id }}" class="item-qty font-bold text-xs text-gray-800">0</span>
                            
                            <button onclick="event.stopPropagation(); updateCart('{{ $menu->id }}', '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, 1)" 
                                class="w-7 h-7 rounded-full bg-[#A8FF00] shadow-sm flex items-center justify-center text-[#182B87] hover:bg-[#95e600] font-bold transition-colors">+</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div> <!-- End Container -->

    <!-- ==========================================
         STICKY BOTTOM CART (Keranjang)
         ========================================== -->
    <div id="cart-bar" class="hidden fixed bottom-4 left-0 right-0 z-50 px-4 flex justify-center">
        <div class="w-full max-w-md bg-[#182B87] rounded-2xl shadow-xl p-4 flex justify-between items-center text-white cursor-pointer hover:bg-blue-900 transition-all"
             onclick="window.location.href='{{ route('customer.checkout') }}'">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border border-white/20" id="cart-total-qty">0</div>
                <div>
                    <p class="text-[10px] font-bold text-blue-200 uppercase tracking-wider mb-0.5">Total Pesanan</p>
                    <p class="font-bold text-lg leading-none" id="cart-total-price">Rp0</p>
                </div>
            </div>
            <div class="flex items-center gap-2 font-bold text-sm text-[#A8FF00] bg-white/10 px-4 py-2 rounded-xl">
                Checkout
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGIKA -->
    <script>
        // --- 1. FILTER KATEGORI ---
        function filterMenu(catId) {
            // Reset style semua tombol kategori
            document.querySelectorAll('.cat-btn').forEach(btn => {
                btn.classList.remove('bg-[#182B87]', 'text-white');
                btn.classList.add('bg-[#F0F1EA]', 'text-gray-600');
            });
            // Set style tombol yang aktif
            let activeBtn = document.getElementById('btn-cat-' + catId);
            if(activeBtn) {
                activeBtn.classList.remove('bg-[#F0F1EA]', 'text-gray-600');
                activeBtn.classList.add('bg-[#182B87]', 'text-white');
            }

            // Tampilkan/Sembunyikan kartu menu berdasarkan kategori
            document.querySelectorAll('.menu-card').forEach(card => {
                if (catId === 'all' || card.dataset.category == catId) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // --- 2. LOGIKA KERANJANG ---
        let cart = JSON.parse(localStorage.getItem('sicasa_cart')) || [];

        function updateCart(id, nama, harga, change) {
            let existing = cart.find(item => item.id == id);
            
            if (existing) {
                existing.qty += change;
                if (existing.qty <= 0) {
                    cart = cart.filter(item => item.id != id); // Hapus jika qty 0
                }
            } else if (change > 0) {
                cart.push({ id: id, nama: nama, harga: harga, qty: 1 });
            }
            
            localStorage.setItem('sicasa_cart', JSON.stringify(cart));
            renderCart();
        }

        function renderCart() {
            let totalQty = 0;
            let totalPrice = 0;

            // Reset angka dan sembunyikan badge di semua card
            document.querySelectorAll('.item-qty').forEach(el => el.innerText = '0');
            document.querySelectorAll('[id^="badge-"]').forEach(el => el.classList.add('hidden'));

            // Update UI dengan data keranjang
            cart.forEach(item => {
                totalQty += item.qty;
                totalPrice += (item.qty * item.harga);
                
                let qtyDisplay = document.getElementById('qty-' + item.id);
                if (qtyDisplay) qtyDisplay.innerText = item.qty;

                let badgeDisplay = document.getElementById('badge-' + item.id);
                if (badgeDisplay) {
                    badgeDisplay.innerText = item.qty;
                    badgeDisplay.classList.remove('hidden');
                }
            });

            // Tampilkan atau sembunyikan bar Checkout
            let cartBar = document.getElementById('cart-bar');
            if (totalQty > 0) {
                cartBar.classList.remove('hidden');
                document.getElementById('cart-total-qty').innerText = totalQty;
                document.getElementById('cart-total-price').innerText = 'Rp' + totalPrice.toLocaleString('id-ID');
            } else {
                cartBar.classList.add('hidden');
            }
        }

        // Render pertama kali
        renderCart();
    </script>
</body>
</html>