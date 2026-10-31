@extends('layouts.kasir')
@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="font-heading text-2xl font-bold text-ink mb-1">Riwayat Transaksi</h2>
            <p class="text-sm text-text-secondary">Daftar semua pesanan yang sudah selesai (Lunas & Diantar).</p>
        </div>
        <button class="bg-surface border border-gray-300 text-ink px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Export Laporan
        </button>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-surface rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col h-[calc(100vh-140px)]">
        <div class="p-0 overflow-y-auto flex-1">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-bold text-text-secondary uppercase tracking-wider sticky top-0 z-10">
                        <th class="px-6 py-4">ID Pesanan</th>
                        <th class="px-6 py-4">Waktu Selesai</th>
                        <th class="px-6 py-4">Meja / Pemesan</th>
                        <th class="px-6 py-4 text-right">Total Transaksi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-ink">
                                #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-text-secondary">
                                {{ $order->updated_at->format('d M Y - H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-ink">{{ $order->table->nomor_meja ?? 'Take-away' }}</p>
                                <p class="text-xs text-text-secondary mt-0.5">{{ $order->nama_pemesan ?? 'Kasir' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right font-heading font-bold text-brand-blue text-base">
                                Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-50 border border-green-200 text-green-700 text-[10px] font-bold px-2 py-1.5 rounded-md uppercase">
                                    ✔ Selesai
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('kasir.print', $order->id) }}" class="inline-flex items-center gap-1.5 bg-white border border-gray-300 text-ink hover:text-brand-blue hover:border-brand-blue text-xs font-semibold py-1.5 px-3 rounded transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                    Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <p class="text-base font-semibold text-gray-500">Belum ada riwayat transaksi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection