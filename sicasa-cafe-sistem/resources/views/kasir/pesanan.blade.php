@extends('layouts.kasir')

@section('title', 'Pesanan Masuk')

@section('content')
    <!-- HEADER POS -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="font-heading text-2xl font-bold text-ink mb-1">Pesanan Masuk (Dine-in)</h2>
            <p class="text-sm text-text-secondary">Verifikasi pembayaran dan pantau status pesanan pelanggan.</p>
        </div>
        <!-- Tombol Refresh (Sementara sebelum kita pasang Auto-Refresh AJAX) -->
        <button onclick="window.location.reload()" class="bg-white border border-gray-300 text-ink text-sm font-bold px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
            Refresh Layar
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- KOLOM 1: Menunggu Verifikasi Pembayaran -->
        <div class="bg-surface rounded-xl border border-gray-200 shadow-sm flex flex-col h-[calc(100vh-180px)]">
            <div class="bg-amber-100/50 border-b border-amber-200 px-5 py-4 rounded-t-xl">
                <h3 class="font-heading font-bold text-amber-900 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Menunggu Verifikasi
                </h3>
            </div>
            
            <div class="p-4 overflow-y-auto flex-1 space-y-3 bg-gray-50/50">
                <!-- Nanti di sini kita masukkan looping dari backend (Pesanan Baru) -->
                <!-- Tampilan kosong (Empty State) -->
                <div class="flex flex-col items-center justify-center h-full text-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    <p class="text-sm font-medium">Belum ada pesanan masuk...</p>
                </div>
            </div>
        </div>

        <!-- KOLOM 2: Diproses & Lunas (Sesuai Screenshot) -->
        <div class="bg-surface rounded-xl border border-gray-200 shadow-sm flex flex-col h-[calc(100vh-180px)]">
            <div class="bg-brand-blue border-b border-brand-blue px-5 py-4 rounded-t-xl">
                <h3 class="font-heading font-bold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path><path d="m9 12 2 2 4-4"></path></svg>
                    Pesanan Diproses / Lunas
                </h3>
            </div>
            
            <div class="p-4 overflow-y-auto flex-1 space-y-3 bg-gray-50/50">
                
                <!-- Contoh Data Card yang sudah lu punya (Disesuaikan dengan UI baru) -->
                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:border-brand-blue transition-colors">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-heading font-bold text-lg text-ink">Meja 01</h4>
                            <p class="text-xs text-text-secondary mt-0.5">Pemesan: <span class="font-bold text-ink">gyu</span></p>
                        </div>
                        <span class="bg-brand-lime/20 text-green-800 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">LUNAS</span>
                    </div>
                    
                    <div class="pt-3 border-t border-gray-100 flex gap-2">
                        <!-- Tombol Cetak Struk -->
                        <button class="flex-1 bg-ink text-white text-xs font-bold py-2 px-3 rounded flex items-center justify-center gap-2 hover:bg-gray-800 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            Cetak Struk
                        </button>
                        
                        <!-- Nanti tombol "Pesanan Selesai" (Backend nomor 1) kita taruh di sini -->
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:border-brand-blue transition-colors">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-heading font-bold text-lg text-ink">Meja 01</h4>
                            <p class="text-xs text-text-secondary mt-0.5">Pemesan: <span class="font-bold text-ink">gyu</span></p>
                        </div>
                        <span class="bg-brand-lime/20 text-green-800 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">LUNAS</span>
                    </div>
                    
                    <div class="pt-3 border-t border-gray-100 flex gap-2">
                        <button class="flex-1 bg-ink text-white text-xs font-bold py-2 px-3 rounded flex items-center justify-center gap-2 hover:bg-gray-800 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            Cetak Struk
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection