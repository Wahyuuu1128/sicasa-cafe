@extends('layouts.kasir')
@section('title', 'Dashboard Shift')

@section('content')
    <!-- HEADER -->
    <div class="mb-8">
        <h2 class="font-heading text-2xl font-bold text-ink mb-1">
            Selamat bertugas, {{ strtok(Auth::user()->name, ' ') }}! 👋
        </h2>
        <p class="text-sm text-text-secondary">Berikut adalah ringkasan operasional shift kamu hari ini: <span class="font-bold text-ink">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span></p>
    </div>

    <!-- KPI CARDS (Statistik Hari Ini) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Card 1: Total -->
        <div class="bg-surface rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs font-bold text-text-secondary uppercase tracking-wider">Total Pesanan</p>
                <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
            </div>
            <h3 class="font-heading text-3xl font-bold text-ink">{{ $totalPesanan }}</h3>
        </div>

        <!-- Card 2: Menunggu Verifikasi -->
        <div class="bg-surface rounded-xl border border-amber-200 p-5 shadow-sm relative overflow-hidden">
            <!-- Accent Line -->
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400"></div>
            <div class="flex justify-between items-start mb-2 pl-2">
                <p class="text-xs font-bold text-amber-700 uppercase tracking-wider">Perlu Verifikasi</p>
                <div class="p-2 bg-amber-50 rounded-lg text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
            <h3 class="font-heading text-3xl font-bold text-amber-600 pl-2">{{ $menunggu }}</h3>
        </div>

        <!-- Card 3: Diproses -->
        <div class="bg-surface rounded-xl border border-brand-blue/30 p-5 shadow-sm relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-blue"></div>
            <div class="flex justify-between items-start mb-2 pl-2">
                <p class="text-xs font-bold text-brand-blue uppercase tracking-wider">Diproses Dapur</p>
                <div class="p-2 bg-blue-50 rounded-lg text-brand-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
            </div>
            <h3 class="font-heading text-3xl font-bold text-brand-blue pl-2">{{ $diproses }}</h3>
        </div>

        <!-- Card 4: Selesai -->
        <div class="bg-surface rounded-xl border border-green-200 p-5 shadow-sm relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500"></div>
            <div class="flex justify-between items-start mb-2 pl-2">
                <p class="text-xs font-bold text-green-700 uppercase tracking-wider">Selesai Hari Ini</p>
                <div class="p-2 bg-green-50 rounded-lg text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
            <h3 class="font-heading text-3xl font-bold text-green-600 pl-2">{{ $selesai }}</h3>
        </div>
    </div>

    <!-- AREA BAWAH: QUICK ACTIONS & RIWAYAT SINGKAT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Quick Actions -->
        <div class="lg:col-span-1 space-y-4">
            <h3 class="font-heading font-bold text-lg text-ink mb-4">Aksi Cepat</h3>
            
            <a href="{{ route('kasir.pesanan') }}" class="flex items-center gap-4 p-4 bg-ink rounded-xl shadow-sm hover:bg-gray-800 transition-colors group">
                <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-brand-lime group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-white text-base">Pantau Antrean</h4>
                    <p class="text-xs text-gray-400 mt-0.5">Verifikasi pesanan QR pelanggan.</p>
                </div>
            </a>

            <a href="{{ route('kasir.pos') }}" class="flex items-center gap-4 p-4 bg-brand-blue rounded-xl shadow-sm hover:bg-blue-900 transition-colors group">
                <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                </div>
                <div>
                    <h4 class="font-bold text-white text-base">Mesin POS</h4>
                    <p class="text-xs text-blue-200 mt-0.5">Buka kasir untuk pesanan Take-away.</p>
                </div>
            </a>
            
            <a href="{{ route('kasir.menu') }}" class="flex items-center gap-4 p-4 bg-surface border border-gray-200 rounded-xl shadow-sm hover:border-brand-blue transition-colors group">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-ink group-hover:text-brand-blue transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-ink text-base">Cek Stok Menu</h4>
                    <p class="text-xs text-text-secondary mt-0.5">Atur menu tersedia atau habis.</p>
                </div>
            </a>
        </div>

        <!-- Tabel Riwayat Terbaru -->
        <div class="lg:col-span-2">
            <div class="bg-surface rounded-xl border border-gray-200 shadow-sm flex flex-col h-full">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-heading font-bold text-lg text-ink">5 Pesanan Selesai Terakhir</h3>
                    <a href="{{ route('kasir.history') }}" class="text-xs font-bold text-brand-blue hover:underline">Lihat Semua</a>
                </div>
                
                <div class="flex-1 overflow-x-auto p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-text-secondary uppercase tracking-wider">
                                <th class="px-5 py-3">Order ID</th>
                                <th class="px-5 py-3">Meja / Pemesan</th>
                                <th class="px-5 py-3 text-right">Total</th>
                                <th class="px-5 py-3 text-center">Waktu Selesai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($riwayatTerbaru as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm font-bold text-ink">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-5 py-3">
                                        <p class="text-sm font-semibold text-ink">{{ $order->table->nomor_meja ?? 'Take-away' }}</p>
                                        <p class="text-xs text-text-secondary">{{ $order->nama_pemesan ?? 'Kasir' }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-right text-sm font-bold text-brand-blue">
                                        Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 text-center text-xs text-text-secondary">
                                        {{ $order->updated_at->format('H:i') }} WIB
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-400">
                                        Belum ada pesanan yang diselesaikan hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection