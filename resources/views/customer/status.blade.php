@extends('layouts.customer')

@section('content')
<div class="min-h-screen p-6 flex flex-col relative z-10 bg-[#F7F7F2]">
    
    <!-- Dekorasi -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-lime rounded-bl-full opacity-40 -z-10"></div>

    <!-- Header -->
    <div class="text-center mt-8 mb-6">
        <h1 class="font-heading text-2xl font-bold text-brand-blue">Status Pesanan</h1>
        <p class="text-gray-500 text-sm mt-1">Meja {{ $order->table->nomor_meja }} • #SICASA-{{ $order->id }}</p>
    </div>

    <!-- Card Status -->
    <div class="bg-white border-2 border-gray-100 rounded-[2rem] p-6 shadow-sm mb-6 flex flex-col items-center text-center relative overflow-hidden">
        
        @if($order->status_pesanan == 'menunggu_bayar')
            <!-- Status: Menunggu Kasir -->
            <div class="w-20 h-20 bg-amber-100 rounded-full flex justify-center items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-500"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <h2 class="font-heading text-xl font-bold text-gray-800 mb-2">Menunggu Verifikasi</h2>
            <p class="text-sm text-gray-500">Pesanan diterima. Kasir sedang memverifikasi pembayaranmu.</p>
        
        @elseif($order->status_pesanan == 'diproses')
            <!-- Status: Dimasak -->
            <div class="w-20 h-20 bg-blue-100 rounded-full flex justify-center items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-brand-blue"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"></path><line x1="6" y1="17" x2="18" y2="17"></line></svg>
            </div>
            <h2 class="font-heading text-xl font-bold text-gray-800 mb-2">Sedang Disiapkan</h2>
            <p class="text-sm text-gray-500">Pembayaran berhasil! Makananmu sedang dimasak oleh koki Sicasa.</p>

        @else
            <!-- Status: Selesai -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex justify-center items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h2 class="font-heading text-xl font-bold text-gray-800 mb-2">Pesanan Selesai!</h2>
            <p class="text-sm text-gray-500">Silakan ikuti arahan staf kami. Selamat menikmati!</p>
        @endif

        <button onclick="window.location.reload()" class="mt-6 px-6 py-2 bg-[#F0F1EA] text-gray-600 font-bold rounded-full text-sm hover:bg-gray-200 flex gap-2 items-center mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><polyline points="3 3 3 8 8 8"></polyline></svg>
            Refresh Status
        </button>
    </div>

    <!-- Ringkasan Pesanan -->
    <div class="bg-white border border-gray-100 rounded-[2rem] p-5 shadow-sm mt-auto">
        <h3 class="font-bold text-ink mb-3 text-sm">Ringkasan Pesanan</h3>
        <div class="space-y-3 mb-4 max-h-[150px] overflow-y-auto">
            @foreach($order->orderDetails as $detail)
            <div class="flex justify-between items-center text-sm">
                <p class="text-gray-600 font-medium"><span class="font-bold text-ink">{{ $detail->jumlah }}x</span> {{ $detail->menu->nama_menu }}</p>
                <p class="font-bold text-brand-blue">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
        <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
            <p class="text-gray-500 font-bold text-sm">Total</p>
            <p class="font-heading font-bold text-xl text-ink">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endsection