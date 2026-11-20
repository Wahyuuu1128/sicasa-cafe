<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sicasa Cafe - Pesan Sekarang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Space+Grotesk:wght@600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F7F7F2; }
        .font-heading { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Ornamen Background -->
    <div class="absolute top-[-50px] right-[-50px] w-48 h-48 bg-[#A8FF00] rounded-full opacity-80 z-0"></div>
    <div class="absolute bottom-[-30px] left-[-30px] w-32 h-32 bg-[#182B87]/10 rounded-full z-0"></div>

    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-8 relative z-10 border border-gray-100">
        
        <!-- Pesan Error Session -->
        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm text-center font-bold mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="text-center mb-8">
            <div class="inline-block bg-gray-100 p-3 rounded-2xl mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Sicasa Logo" class="h-10 object-contain">
            </div>
            <h1 class="font-heading text-2xl font-bold text-[#182B87]">Selamat Datang!</h1>
            <p class="text-gray-500 text-sm mt-2">Pesan langsung dari HP-mu tanpa ribet antre di kasir.</p>
        </div>

        <!-- FORM INPUT MANUAL -->
        <form action="{{ route('customer.mulai') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="bg-[#F7F7F2] p-5 rounded-2xl border border-gray-200 space-y-4">
                
                <!-- Input Nomor Meja (Manual Ketik) -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Posisi Kamu</label>
                    <input type="number" name="nomor_meja" min="1" max="25" required placeholder="Cth Nomor Meja: 14" 
                        class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[#182B87] focus:border-transparent block p-3.5 font-semibold transition-all">
                </div>

                <!-- Input Nama -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Siapa Namamu?</label>
                    <input type="text" name="nama_pemesan" required placeholder="Cth: Wahyu Rahmat" 
                        class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[#182B87] focus:border-transparent block p-3.5 font-semibold transition-all">
                </div>

            </div>

            <button type="submit" class="w-full bg-[#A8FF00] hover:bg-[#95e600] text-[#182B87] font-heading font-bold text-lg py-4 rounded-xl transition-all shadow-md flex justify-center items-center gap-2">
                Mulai Pesan
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-8 font-medium">Powered by Sicasa POS</p>
    </div>

</body>
</html>