@extends('layouts.owner') <!-- Ganti ke layouts.kasir jika lu pakai layout kasir buat owner -->
@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <a href="{{ route('owner.export') }}" class="flex justify-center items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-4 py-2.5 rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Export Excel
            </a>
        
        
        <!-- Filter Pintar Dropdown (Mobile Friendly) -->
        <form action="{{ route('owner.history') }}" method="GET" class="w-full md:w-auto">
            <select name="filter" onchange="this.form.submit()" class="w-full md:w-auto bg-surface border border-gray-300 text-sm font-bold text-ink px-4 py-2.5 rounded-lg shadow-sm outline-none cursor-pointer focus:border-brand-blue">
                <option value="semua" {{ $filter == 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="this_week" {{ $filter == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="this_month" {{ $filter == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
        </form>

    </div>

    <!-- LIST CARD RIWAYAT (TANPA TABEL = ANTI SCROLL HORIZONTAL) -->
    <div class="space-y-4 pb-10">
        @forelse($orders as $order)
            <!-- Setiap kotak dikendalikan Alpine.js (x-data="{ open: false }") untuk buka-tutup rincian -->
            <div x-data="{ open: false }" class="bg-surface border border-gray-200 rounded-xl shadow-sm overflow-hidden transition-all">
                
                <!-- Card Header (Klik untuk buka detail) -->
                <div @click="open = !open" class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 cursor-pointer hover:bg-gray-50">
                    
                    <div class="flex justify-between items-start md:w-1/3">
                        <div>
                            <h3 class="font-heading font-bold text-brand-blue text-lg">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h3>
                            <p class="text-xs text-text-secondary mt-0.5 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                {{ $order->updated_at->format('d M Y - H:i') }}
                            </p>
                        </div>
                        <span class="bg-green-100 text-green-700 border border-green-200 text-[10px] px-2 py-1 rounded-md font-bold uppercase md:hidden">Selesai</span>
                    </div>

                    <div class="flex flex-row md:w-1/3 justify-between md:justify-center items-center gap-6 border-t border-dashed border-gray-200 md:border-none pt-3 md:pt-0">
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold mb-0.5">Tipe & Nama</p>
                            <p class="text-sm font-bold text-ink">{{ $order->table_id ? 'Dine-in (Meja '.$order->table->nomor_meja.')' : 'Take-away' }}</p>
                            <p class="text-xs text-text-secondary truncate max-w-[120px]">{{ $order->nama_pemesan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold mb-0.5">Pembayaran</p>
                            <p class="text-sm font-bold {{ $order->payment == 'QRIS' ? 'text-brand-blue' : 'text-ink' }}">{{ $order->payment ?? 'Tunai' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center md:w-1/3 md:justify-end gap-4 border-t border-dashed border-gray-200 md:border-none pt-3 md:pt-0">
                        <div class="text-left md:text-right">
                            <p class="text-[10px] uppercase text-gray-400 font-bold mb-0.5">Total Tagihan</p>
                            <p class="font-heading font-bold text-lg text-ink">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-gray-400 transform transition-transform" :class="open ? 'rotate-180' : ''">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>

                </div>

                <!-- Card Body (Rincian Item yang Dipesan) -->
                <div x-show="open" x-transition class="bg-gray-50 border-t border-gray-100 p-4">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Rincian Pesanan</h4>
                    
                    <div class="space-y-3">
                        @forelse($order->orderDetails as $detail)
                            <div class="flex justify-between items-center bg-white border border-gray-200 p-3 rounded-lg shadow-sm">
                                <div>
                                    <p class="text-sm font-bold text-ink">{{ $detail->menu->nama_menu ?? 'Menu Terhapus' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Rp{{ number_format($detail->harga, 0, ',', '.') }} x {{ $detail->jumlah }} porsi</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-brand-blue">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Rincian item tidak tersedia.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-surface border border-gray-200 rounded-xl p-10 text-center shadow-sm">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <h3 class="font-bold text-ink mb-1">Belum Ada Transaksi</h3>
                <p class="text-sm text-text-secondary">Tidak ada data riwayat penjualan untuk rentang waktu ini.</p>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection