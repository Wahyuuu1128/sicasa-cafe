@extends('layouts.customer')

@section('content')
<div x-data="cart()" class="pb-24">
    
    <!-- HEADER STICKY -->
    <div class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 p-4 shadow-sm flex justify-between items-center">
        <div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Meja {{ $table->nomor_meja }}</p>
            <h2 class="font-heading text-lg font-bold text-brand-blue truncate max-w-[200px]">Hai, {{ session('nama_pemesan') }}!</h2>
        </div>
        <div class="w-10 h-10 bg-[#F0F1EA] rounded-full flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-brand-blue"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </div>
    </div>

    <!-- KATEGORI CHIPS -->
    <div class="flex overflow-x-auto gap-3 p-4 no-scrollbar">
        <button class="whitespace-nowrap px-5 py-2 rounded-full font-bold text-sm bg-brand-blue text-white shadow-md">Semua</button>
        
        <!-- BYPASS ERROR LARAVEL: PAKAI PHP NATIVE -->
        <?php foreach($categories as$cat): ?>
            <button class="whitespace-nowrap px-5 py-2 rounded-full font-bold text-sm bg-[#F0F1EA] text-gray-600 hover:bg-gray-200 transition-colors">{{ $cat->nama_kategori }}</button>
        <?php endforeach; ?>
    </div>

    <!-- LIST MENU -->
    <div class="p-4 grid grid-cols-2 gap-4">
        
        <!-- BYPASS ERROR LARAVEL: PAKAI PHP NATIVE -->
        <?php foreach($menus as$menu): ?>
            <div class="bg-white border border-gray-100 rounded-2xl p-3 shadow-sm flex flex-col relative overflow-hidden">
                <div class="w-full aspect-square bg-gray-100 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                    @if($menu->foto)
                        <img src="{{ asset('storage/' . $menu->foto) }}" class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-gray-300"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    @endif
                </div>
                
                <h3 class="font-heading font-bold text-ink text-sm leading-tight mb-1">{{ $menu->nama_menu }}</h3>
                <p class="text-xs text-brand-blue font-bold mt-auto mb-3">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                
                <button @click="add({{ $menu->id }}, '{{ addslashes($menu->nama_menu) }}', {{$menu->harga }})" class="w-full bg-brand-lime text-brand-blue font-bold text-xs py-2 rounded-xl shadow-sm hover:scale-105 active:scale-95 transition-transform">
                    + Tambah
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- STICKY FLOATING CART BAR -->
    <div x-show="totalQty > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
         class="fixed bottom-0 left-0 right-0 max-w-md mx-auto p-4 z-50">
        <div @click="isCartOpen = true" class="bg-brand-blue text-white rounded-2xl p-4 shadow-xl flex justify-between items-center cursor-pointer hover:bg-blue-900 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-lime text-brand-blue rounded-full flex justify-center items-center font-bold text-sm">
                    <span x-text="totalQty"></span>
                </div>
                <div>
                    <p class="text-xs text-blue-200">Total Pesanan</p>
                    <p class="font-bold text-lg leading-none" x-text="formatRupiah(totalPrice)"></p>
                </div>
            </div>
            <div class="flex items-center gap-2 font-bold text-sm text-brand-lime">
                Cek Keranjang
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </div>
        </div>
    </div>

    <!-- MODAL KERANJANG -->
    <div x-show="isCartOpen" style="display: none;" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center">
        <div @click="isCartOpen = false" x-show="isCartOpen" x-transition.opacity class="absolute inset-0 bg-black/50 max-w-md mx-auto"></div>
        
        <div x-show="isCartOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
             class="relative bg-white w-full max-w-md rounded-t-[2rem] h-[80vh] flex flex-col shadow-2xl">
            
            <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-heading font-bold text-xl text-brand-blue">Keranjangmu</h2>
                <button @click="isCartOpen = false" class="w-8 h-8 bg-gray-100 rounded-full flex justify-center items-center text-gray-500 font-bold">X</button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 space-y-4">
                <template x-for="item in items" :key="item.id">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                        <div class="flex-1">
                            <p class="font-bold text-ink text-sm" x-text="item.nama"></p>
                            <p class="text-xs text-brand-blue font-bold" x-text="formatRupiah(item.harga * item.qty)"></p>
                        </div>
                        
                        <div class="flex items-center gap-3 bg-[#F0F1EA] rounded-full px-2 py-1">
                            <button @click="decrease(item.id)" class="w-6 h-6 bg-white rounded-full flex justify-center items-center font-bold text-brand-blue shadow-sm">-</button>
                            <span class="text-sm font-bold text-ink w-4 text-center" x-text="item.qty"></span>
                            <button @click="increase(item.id)" class="w-6 h-6 bg-brand-lime rounded-full flex justify-center items-center font-bold text-brand-blue shadow-sm">+</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-5 border-t border-gray-100 bg-white rounded-b-[2rem]">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-gray-500">Total Pembayaran</span>
                    <span class="font-heading font-bold text-2xl text-ink" x-text="formatRupiah(totalPrice)"></span>
                </div>
                
                <button @click="processCheckout({{ $table->id }})" :disabled="isProcessing" 
                        class="w-full bg-brand-lime text-brand-blue font-bold text-lg py-4 rounded-2xl shadow-md hover:scale-[1.02] active:scale-[0.98] transition-transform flex justify-center items-center gap-2">
                    <span x-show="!isProcessing">Bayar Sekarang</span>
                    <span x-show="isProcessing">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cart', () => ({
        items: [],
        isCartOpen: false,
        isProcessing: false,
        
        get totalQty() { return this.items.reduce((sum, item) => sum + item.qty, 0); },
        get totalPrice() { return this.items.reduce((sum, item) => sum + (item.qty * item.harga), 0); },
        
        add(id, nama, harga) {
            let existing = this.items.find(i => i.id === id);
            if (existing) { existing.qty++; }
            else { this.items.push({ id: id, nama: nama, harga: harga, qty: 1 }); }
        },
        increase(id) {
            let item = this.items.find(i => i.id === id);
            if(item) item.qty++;
        },
        decrease(id) {
            let item = this.items.find(i => i.id === id);
            if(item) {
                item.qty--;
                if(item.qty === 0) this.items = this.items.filter(i => i.id !== id);
                if(this.items.length === 0) this.isCartOpen = false;
            }
        },
        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        },
        
        async processCheckout(tableId) {
            this.isProcessing = true;
            try {
                const response = await fetch(`/qr/meja/${tableId}/checkout`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: JSON.stringify(this.items),
                        total_harga: this.totalPrice
                    })
                });
                
                // JIKA LARAVEL ERROR, TAMPILKAN PESAN ASLINYA!
                if (!response.ok) {
                    const errData = await response.json();
                    alert("ERROR DARI SERVER:\n" + errData.error);
                    this.isProcessing = false;
                    return;
                }
                
                const data = await response.json();
                
                if (data.token) {
                    snap.pay(data.token, {
                        onSuccess: function(result){ window.location.href = `/qr/order/${data.order_id}`; },
                        onPending: function(result){ window.location.href = `/qr/order/${data.order_id}`; },
                        onError: function(result){ alert('Pembayaran gagal/batal!'); this.isProcessing = false; },
                        onClose: () => { this.isProcessing = false; }
                    });
                }
            } catch (error) {
                alert('Gagal menyambung ke server Laravel.');
                this.isProcessing = false;
            }
        }
    }));
});
</script>
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush