@extends('layouts.customer')

@section('content')
<div class="flex flex-col min-h-screen p-6 relative z-10">
    
    <!-- Elemen Dekorasi Ala Playful Minimalism -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-lime rounded-bl-full opacity-40 -z-10"></div>
    <div class="absolute bottom-10 left-[-20px] w-24 h-24 bg-brand-blue rounded-full opacity-10 -z-10"></div>

    <!-- Header Logo -->
    <div class="mt-12 mb-8 text-center">
        <!-- Logo Sicasa (Ubah path kalau logonya beda) -->
        <img src="{{ asset('images/logo.png') }}" alt="Sicasa Logo" class="h-16 mx-auto mb-4 grayscale opacity-90 mix-blend-multiply">
        <h1 class="font-heading text-3xl font-bold text-brand-blue">Selamat Datang!</h1>
        <p class="text-gray-500 text-sm mt-2">Pesan langsung dari HP-mu tanpa ribet antre di kasir.</p>
    </div>

    <!-- Kotak Input Nama -->
    <div class="bg-white border-2 border-gray-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-center relative">
        
        <div class="text-center mb-6">
            <span class="inline-block bg-[#F0F1EA] text-gray-600 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-2">
                Posisi Kamu
            </span>
            <h2 class="font-heading text-4xl font-bold text-gray-800">Meja {{ $table->nomor_meja }}</h2>
        </div>

        <form action="{{ route('customer.setNama', $table->id) }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Siapa namamu?</label>
                <input type="text" name="nama_pemesan" required placeholder="Cth: Wahyu Rahmat" 
                    class="w-full bg-[#F7F7F2] border-none text-gray-800 px-5 py-4 rounded-2xl focus:ring-2 focus:ring-brand-lime outline-none transition-all placeholder-gray-400 font-medium text-lg">
                @error('nama_pemesan')
                    <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-brand-lime text-brand-blue font-bold text-lg py-4 rounded-2xl shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-transform mt-2 flex items-center justify-center gap-2">
                Mulai Pesan 
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="text-center mt-auto pt-8">
        <p class="text-xs text-gray-400 font-medium">Powered by Sicasa POS</p>
    </div>
</div>
@endsection