@extends('layouts.kasir')
@section('title', 'Point of Sales')

@section('content')
    <div x-data="posSystem()" class="flex flex-col h-[calc(100vh-60px)] -mt-2">
        
        <div class="mb-4">
            <h2 class="font-heading text-2xl font-bold text-ink mb-1">POS (Take-away)</h2>
            <p class="text-sm text-text-secondary">Pilih menu, hitung tagihan, dan selesaikan transaksi.</p>
        </div>

        <div class="flex-1 flex flex-col lg:flex-row gap-6 overflow-hidden pb-4">
            
            <!-- KOLOM KIRI: KATALOG MENU -->
            <div class="w-full lg:w-7/12 xl:w-2/3 bg-surface rounded-xl border border-gray-200 shadow-sm flex flex-col overflow-hidden h-[50vh] lg:h-full">
                <div class="p-4 border-b border-gray-100 flex gap-2 overflow-x-auto hide-scrollbar bg-gray-50/50">
                    <button @click="kategoriAktif = 'semua'" :class="kategoriAktif === 'semua' ? 'bg-ink text-white' : 'bg-white border-gray-200 text-text-secondary hover:bg-gray-100'" class="px-4 py-2 border rounded-full text-xs font-bold whitespace-nowrap transition-colors shadow-sm">Semua Menu</button>
                    @foreach($categories as $cat)
                        <button @click="kategoriAktif = '{{ $cat->id }}'" :class="kategoriAktif === '{{ $cat->id }}' ? 'bg-ink text-white' : 'bg-white border-gray-200 text-text-secondary hover:bg-gray-100'" class="px-4 py-2 border rounded-full text-xs font-bold whitespace-nowrap transition-colors shadow-sm">{{ $cat->nama_kategori }}</button>
                    @endforeach
                </div>

                <div class="flex-1 overflow-y-auto p-4 bg-gray-50/30">
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($menus as $menu)
                            <div x-show="kategoriAktif === 'semua' || kategoriAktif === '{{ $menu->category_id }}'" 
                                 @click="tambahKeKeranjang({{ $menu->id }}, '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, '{{ $menu->foto ? asset('storage/'.$menu->foto) : '' }}')"
                                 class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-brand-blue hover:shadow-md transition-all cursor-pointer group flex flex-col">
                                <div class="h-28 bg-gray-100 overflow-hidden relative flex-shrink-0">
                                    @if($menu->foto)
                                        <img src="{{ asset('storage/'.$menu->foto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <span class="bg-brand-lime text-ink text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg">+ Keranjang</span>
                                    </div>
                                </div>
                                <div class="p-3 flex-1 flex flex-col justify-between">
                                    <h4 class="font-bold text-xs text-ink line-clamp-2 leading-tight">{{ $menu->nama_menu }}</h4>
                                    <p class="font-heading font-bold text-brand-blue mt-2 text-sm">Rp<span x-text="formatRupiah({{ $menu->harga }})"></span></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: KERANJANG -->
            <div class="w-full lg:w-5/12 xl:w-1/3 bg-surface rounded-xl border border-gray-200 shadow-sm flex flex-col overflow-hidden h-[50vh] lg:h-full shrink-0">
                <div class="bg-brand-blue px-5 py-4 flex justify-between items-center shrink-0">
                    <h3 class="font-heading font-semibold text-sm text-white flex items-center gap-2">Keranjang Pesanan</h3>
                    <span x-show="keranjang.length > 0" x-text="keranjang.length + ' Item'" class="bg-white/20 text-white text-[10px] px-2 py-0.5 rounded-full font-bold"></span>
                </div>
                
                <div class="flex-1 overflow-y-auto bg-gray-50/50 p-2 space-y-2">
                    <div x-show="keranjang.length === 0" class="h-full flex flex-col items-center justify-center opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 text-gray-400"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg>
                        <p class="text-xs font-medium text-gray-500">Keranjang masih kosong</p>
                    </div>

                    <template x-for="(item, index) in keranjang" :key="index">
                        <div class="bg-white border border-gray-200 p-3 rounded-lg flex justify-between items-center shadow-sm relative group">
                            <div class="flex-1 pr-2">
                                <h5 class="text-xs font-bold text-ink truncate" x-text="item.nama"></h5>
                                <p class="text-[10px] text-gray-500 mt-0.5">Rp<span x-text="formatRupiah(item.harga)"></span> / item</p>
                            </div>
                            <div class="flex items-center gap-2 bg-gray-50 rounded-md border border-gray-200 p-1">
                                <button @click="kurangiItem(index)" class="w-6 h-6 rounded flex items-center justify-center text-gray-500 hover:bg-red-100 hover:text-red-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"></line></svg></button>
                                <span class="text-xs font-bold text-ink w-4 text-center" x-text="item.qty"></span>
                                <button @click="tambahItem(index)" class="w-6 h-6 rounded flex items-center justify-center text-gray-500 hover:bg-brand-lime hover:text-ink transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></button>
                            </div>
                            <div class="w-16 text-right ml-2"><p class="text-xs font-bold text-brand-blue">Rp<span x-text="formatRupiah(item.harga * item.qty)"></span></p></div>
                        </div>
                    </template>
                </div>
                
                <div class="p-4 border-t border-gray-200 bg-white shrink-0">
                    <div class="flex justify-between items-center font-bold text-ink mb-3">
                        <span class="text-xs uppercase tracking-wider text-gray-500">Total Tagihan</span>
                        <span class="font-heading text-2xl text-ink">Rp <span x-text="formatRupiah(totalTagihan)"></span></span>
                    </div>

                    <!-- Input Nama & Pilihan Pembayaran -->
                    <div x-show="keranjang.length > 0" class="mb-4 space-y-3">
                        <input type="text" x-model="namaPemesan" placeholder="Nama Pembeli (Opsional)..." class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs outline-none focus:border-brand-blue focus:ring-1">
                        
                        <div class="flex gap-2">
                            <button type="button" @click="metodePembayaran = 'Tunai'" :class="metodePembayaran === 'Tunai' ? 'bg-ink text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'" class="flex-1 py-2 rounded-md text-xs font-bold transition-colors">Tunai</button>
                            <button type="button" @click="metodePembayaran = 'QRIS'" :class="metodePembayaran === 'QRIS' ? 'bg-brand-blue text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'" class="flex-1 py-2 rounded-md text-xs font-bold transition-colors">QRIS</button>
                        </div>

                        <!-- Input Uang Muncul Cuma Kalau Tunai -->
                        <div x-show="metodePembayaran === 'Tunai'" x-transition>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Uang Diterima (Rp)</label>
                            <input type="number" x-model="uangBayar" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm font-bold text-brand-blue outline-none focus:border-brand-blue text-right" placeholder="Masukkan jumlah uang...">
                            <div x-show="uangBayar > 0" class="flex justify-between items-center mt-2 px-1">
                                <span class="text-[10px] font-bold uppercase text-gray-500">Kembalian:</span>
                                <span :class="(uangBayar - totalTagihan) < 0 ? 'text-red-500' : 'text-green-600'" class="text-sm font-bold">
                                    Rp <span x-text="formatRupiah(uangBayar - totalTagihan)"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Proses Pembayaran -->
                    <form action="{{ route('kasir.pos.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="total_harga" :value="totalTagihan">
                        <input type="hidden" name="uang_bayar" :value="metodePembayaran === 'QRIS' ? totalTagihan : uangBayar">
                        <input type="hidden" name="metode_pembayaran" :value="metodePembayaran">
                        <input type="hidden" name="nama_pemesan" :value="namaPemesan">
                        <input type="hidden" name="items" :value="JSON.stringify(keranjang)">
                        
                        <button type="submit" 
                                :disabled="keranjang.length === 0 || (metodePembayaran === 'Tunai' && uangBayar < totalTagihan)" 
                                :class="keranjang.length === 0 || (metodePembayaran === 'Tunai' && uangBayar < totalTagihan) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-brand-lime text-ink hover:bg-[#bbf011] shadow-sm'"
                                class="w-full font-bold py-3 rounded-lg transition-colors flex justify-center items-center gap-2 text-sm uppercase tracking-wide">
                            <span x-text="(metodePembayaran === 'Tunai' && uangBayar < totalTagihan && totalTagihan > 0) ? 'Uang Kurang!' : 'Selesaikan Pembayaran'"></span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL SUKSES BAYAR (MUNCUL OTOMATIS) -->
    @if(session('sukses_bayar'))
        <div id="modalSukses" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-surface rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center transform scale-100 transition-transform">
                <div class="w-20 h-20 rounded-full bg-green-100 text-green-500 flex items-center justify-center mx-auto mb-5 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h3 class="font-heading font-bold text-2xl text-ink mb-2">Pembayaran Berhasil!</h3>
                <p class="text-sm text-text-secondary mb-8">Pesanan Take-away telah tercatat di sistem & Riwayat Transaksi.</p>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('kasir.print', session('sukses_bayar')) }}" target="_blank" class="w-full bg-brand-blue text-white font-bold py-3.5 rounded-xl hover:bg-blue-800 transition-colors shadow-sm flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        Cetak Struk
                    </a>
                    <button onclick="document.getElementById('modalSukses').style.display='none'" class="w-full bg-gray-100 text-ink font-semibold py-3.5 rounded-xl hover:bg-gray-200 transition-colors">
                        Kembali (Pesanan Baru)
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posSystem', () => ({
                kategoriAktif: 'semua',
                keranjang: [],
                uangBayar: '',
                namaPemesan: '',
                metodePembayaran: 'Tunai', // Default bayar pakai uang cash
                
                get totalTagihan() { return this.keranjang.reduce((total, item) => total + (item.harga * item.qty), 0); },
                formatRupiah(angka) { return new Intl.NumberFormat('id-ID').format(angka); },
                
                tambahKeKeranjang(id, nama, harga, foto) {
                    const itemIndex = this.keranjang.findIndex(item => item.id === id);
                    if (itemIndex > -1) this.keranjang[itemIndex].qty++;
                    else this.keranjang.push({ id, nama, harga, foto, qty: 1 });
                },
                tambahItem(index) { this.keranjang[index].qty++; },
                kurangiItem(index) {
                    if (this.keranjang[index].qty > 1) this.keranjang[index].qty--;
                    else this.keranjang.splice(index, 1);
                }
            }))
        })
    </script>
@endsection