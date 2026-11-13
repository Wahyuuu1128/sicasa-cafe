@extends('layouts.kasir')
@section('title', 'Pesanan Masuk')

@section('content')
<div class="p-6">
    <!-- HEADER & TOMBOL BUKA SCANNER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#182B87] tracking-tight">Pesanan Masuk (Dine-in)</h1>
            <p class="text-gray-500 text-sm">Dashboard real-time. Klik kartu untuk detail, atau scan barcode tunai.</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <!-- Tombol Scanner Utama -->
            <button onclick="openScannerModal()" class="flex-1 md:flex-none bg-[#182B87] hover:bg-blue-900 text-white px-4 py-2.5 rounded-lg flex justify-center items-center gap-2 text-sm font-bold shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7V4h3"></path><path d="M20 7V4h-3"></path><path d="M4 17v3h3"></path><path d="M20 17v3h-3"></path><line x1="12" y1="9" x2="12" y2="15"></line></svg>
                Scan Barcode
            </button>
            <!-- Indikator Auto-Refresh -->
            <div class="flex items-center gap-2 bg-[#F0F1EA] px-4 py-2.5 rounded-lg border border-gray-200">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#A8FF00] opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-[#85cc00]"></span>
                </span>
                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider hidden sm:block">Live Update</span>
            </div>
        </div>
    </div>

    <!-- WRAPPER UNTUK AUTO-REFRESH AJAX -->
    <div id="pos-board" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- ==========================================
             KOLOM 1: MENUNGGU VERIFIKASI 
             ========================================== -->
        <div class="bg-[#FFF8E7] rounded-2xl p-5 border border-amber-200/60 shadow-sm">
            <h3 class="font-bold text-amber-900 mb-4 flex items-center gap-2 text-sm uppercase tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                Menunggu Verifikasi ({{ $pesananBaru->count() }})
            </h3>

            @if($pesananBaru->isEmpty())
                <div class="text-center py-12 text-amber-600/40 flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    <p class="text-sm font-medium">Belum ada pesanan masuk</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($pesananBaru as $order)
                        <!-- COMPACT CARD -->
                        <div onclick="openModal('modal-baru-{{ $order->id }}')" class="bg-white p-4 rounded-xl border border-amber-100 shadow-sm hover:shadow-md hover:border-amber-300 cursor-pointer transition-all flex flex-col justify-between h-full group">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Baru</span>
                                    <h4 class="font-bold text-gray-900 mt-1.5 leading-tight">Meja {{ str_replace('Meja ', '', $order->table->nomor_meja ?? '-') }}</h4>
                                </div>
                                <span class="text-gray-400 group-hover:text-amber-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 truncate mb-1">{{ $order->nama_pemesan }}</p>
                                <div class="flex justify-between items-end">
                                    <p class="font-bold text-amber-600 text-sm">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $order->payment }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL POP-UP DETAIL PESANAN BARU -->
                        <div id="modal-baru-{{ $order->id }}" class="hidden fixed inset-0 z-[50] bg-[#17191C]/60 backdrop-blur-sm flex items-center justify-center p-4">
                            <div class="bg-white rounded-[1.5rem] w-full max-w-md overflow-hidden shadow-2xl relative" onclick="event.stopPropagation()">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Order #{{ $order->id }}</p>
                                            <h2 class="text-2xl font-bold text-[#182B87]">Meja {{ str_replace('Meja ', '', $order->table->nomor_meja ?? '-') }}</h2>
                                            <p class="text-sm text-gray-600 mt-1">Pemesan: <span class="font-bold text-gray-900">{{ $order->nama_pemesan }}</span></p>
                                        </div>
                                        <button onclick="closeModal('modal-baru-{{ $order->id }}')" class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-full transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>

                                    <div class="bg-[#F0F1EA] p-4 rounded-xl mb-6">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-200 pb-2">Rincian Menu</p>
                                        <ul class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                            @foreach($order->orderDetails as $detail)
                                                <li class="flex justify-between items-start text-sm">
                                                    <span class="font-medium text-gray-800"><span class="font-bold text-[#182B87] mr-1">{{ $detail->jumlah }}x</span> {{ $detail->menu->nama_menu }}</span>
                                                    <span class="text-gray-600 text-xs mt-0.5">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="flex justify-between items-center mb-6">
                                        <span class="text-sm font-bold text-gray-500 uppercase">Total Tagihan</span>
                                        <span class="text-xl font-bold text-[#182B87]">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                    </div>

                                    <!-- LOGIKA TOMBOL BERDASARKAN METODE BAYAR -->
                                    @if(strtolower($order->payment) == 'tunai')
                                        <button type="button" onclick="openScannerModal()" class="w-full bg-[#182B87] hover:bg-blue-900 text-white font-bold py-3.5 rounded-xl transition-all flex justify-center items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7V4h3"></path><path d="M20 7V4h-3"></path><path d="M4 17v3h3"></path><path d="M20 17v3h-3"></path><line x1="12" y1="9" x2="12" y2="15"></line></svg>
                                            Scan Barcode Tunai
                                        </button>
                                    @else
                                        <form action="{{ route('kasir.verify', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 rounded-xl transition-all flex justify-center items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                Verifikasi Pembayaran QRIS
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- ==========================================
             KOLOM 2: PESANAN DIPROSES / LUNAS 
             ========================================== -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
            <h3 class="font-bold text-[#182B87] mb-4 flex items-center gap-2 text-sm uppercase tracking-wide">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"></path><line x1="6" y1="17" x2="18" y2="17"></line></svg>
                Diproses / Lunas ({{ $diproses->count() }})
            </h3>

            @if($diproses->isEmpty())
                <div class="text-center py-12 text-gray-300 flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                    <p class="text-sm font-medium">Belum ada pesanan diproses</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($diproses as $order)
                        <!-- COMPACT CARD -->
                        <div onclick="openModal('modal-proses-{{ $order->id }}')" class="bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-[#182B87]/30 cursor-pointer transition-all flex flex-col justify-between h-full group">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="bg-[#A8FF00]/20 text-[#182B87] text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">Dimasak</span>
                                    <h4 class="font-bold text-gray-900 mt-1.5 leading-tight">Meja {{ str_replace('Meja ', '', $order->table->nomor_meja ?? '-') }}</h4>
                                </div>
                                <span class="text-gray-400 group-hover:text-[#182B87] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 truncate mb-1">{{ $order->nama_pemesan }}</p>
                                <p class="font-bold text-[#182B87] text-sm">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- MODAL POP-UP DETAIL PESANAN DIPROSES -->
                        <div id="modal-proses-{{ $order->id }}" class="hidden fixed inset-0 z-[50] bg-[#17191C]/60 backdrop-blur-sm flex items-center justify-center p-4">
                            <div class="bg-white rounded-[1.5rem] w-full max-w-md overflow-hidden shadow-2xl relative" onclick="event.stopPropagation()">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Order #{{ $order->id }}</p>
                                            <h2 class="text-2xl font-bold text-[#182B87]">Meja {{ str_replace('Meja ', '', $order->table->nomor_meja ?? '-') }}</h2>
                                            <p class="text-sm text-gray-600 mt-1">Pemesan: <span class="font-bold text-gray-900">{{ $order->nama_pemesan }}</span></p>
                                        </div>
                                        <button onclick="closeModal('modal-proses-{{ $order->id }}')" class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-full transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>

                                    <div class="bg-[#F0F1EA] p-4 rounded-xl mb-6">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 border-b border-gray-200 pb-2">Rincian Menu</p>
                                        <ul class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                            @foreach($order->orderDetails as $detail)
                                                <li class="flex justify-between items-start text-sm">
                                                    <span class="font-medium text-gray-800"><span class="font-bold text-[#182B87] mr-1">{{ $detail->jumlah }}x</span> {{ $detail->menu->nama_menu }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="flex gap-3 mt-2">
                                        <!-- Tombol Cetak -->
                                        <a href="{{ route('kasir.print', $order->id) }}" target="_blank" class="w-1/3 bg-[#17191C] hover:bg-gray-800 text-white flex justify-center items-center py-3.5 rounded-xl text-sm font-bold transition-colors" title="Cetak Struk">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        </a>
                                        
                                        <!-- Tombol Selesai -->
                                        <form action="{{ route('kasir.complete', $order->id) }}" method="POST" class="w-2/3">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Tandai pesanan ini selesai dan meja tersedia kembali?')" class="w-full bg-[#A8FF00] hover:bg-[#95e600] text-[#182B87] flex justify-center items-center gap-2 py-3.5 rounded-xl text-sm font-bold transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                Tandai Selesai
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
    </div>
</div>

<!-- ==========================================
     MODAL GLOBAL SCANNER (Tersembunyi)
     ========================================== -->
<div id="scannerModal" class="hidden fixed inset-0 z-[60] bg-[#17191C]/90 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[1.5rem] w-full max-w-md overflow-hidden shadow-2xl relative">
        
        <!-- Header Scanner -->
        <div class="bg-[#182B87] text-white px-6 py-4 flex justify-between items-center">
            <h3 class="font-bold tracking-wider text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A8FF00" stroke-width="2"><path d="M4 7V4h3"></path><path d="M20 7V4h-3"></path><path d="M4 17v3h3"></path><path d="M20 17v3h-3"></path><line x1="12" y1="9" x2="12" y2="15"></line></svg>
                SCANNER PEMBAYARAN
            </h3>
            <button onclick="closeScannerModal()" class="text-white/70 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div class="p-6 bg-gray-50">
            <!-- Tampilan Kamera -->
            <div id="reader" class="w-full rounded-xl overflow-hidden border-2 border-dashed border-gray-300 mb-4 bg-white"></div>
            
            <!-- Input Manual Fisik -->
            <form id="manualForm" onsubmit="handleManualSubmit(event)">
                <div class="relative">
                    <input type="text" id="manual_code" placeholder="Atau Input Manual (Cth: SICASA-44)" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#182B87] focus:border-[#182B87] block p-3 text-center uppercase font-bold" required autocomplete="off">
                </div>
                <!-- Tombol submit disembunyikan, biar kasir cuma perlu pencet Enter -->
                <button type="submit" class="hidden">Submit</button>
            </form>
            
            <!-- Kotak Peringatan/Sukses -->
            <div id="scanResult" class="hidden mt-4 p-4 rounded-xl text-center font-bold text-sm text-white shadow-md"></div>
        </div>
    </div>
</div>

<!-- LIBRARY HTML5 QRCODE -->
<script src="https://unpkg.com/html5-qrcode"></script>

<!-- SCRIPT UNTUK MODAL, SCANNER & AUTO REFRESH (AJAX) -->
<script>
    let isModalOpen = false;
    let html5QrcodeScanner = null;
    let isProcessing = false;

    // FUNGSI MODAL PESANAN
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Kunci scroll background
        isModalOpen = true;
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = ''; // Buka kunci scroll
        isModalOpen = false;
    }

    // FUNGSI MODAL SCANNER GLOBAL
    function openScannerModal() {
        document.querySelectorAll('[id^="modal-baru-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="modal-proses-"]').forEach(el => el.classList.add('hidden'));
        
        document.getElementById('scannerModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Kunci scroll background untuk scanner
        isModalOpen = true;

        if(!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
                fps: 10, 
                qrbox: {width: 250, height: 250},
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            }, false);
        }
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        document.getElementById('scanResult').classList.add('hidden');
        document.getElementById('manual_code').value = '';
        setTimeout(() => document.getElementById('manual_code').focus(), 500);
    }

    function closeScannerModal() {
        document.getElementById('scannerModal').classList.add('hidden');
        document.body.style.overflow = ''; // Buka kunci scroll saat scanner ditutup
        isModalOpen = false;
        isProcessing = false;
        
        if(html5QrcodeScanner) {
            html5QrcodeScanner.clear().catch(error => {
                console.error("Gagal mematikan kamera.", error);
            });
        }
    }

    // SUARA BEEP KASIR
    function playBeep() {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        oscillator.type = 'sine';
        oscillator.frequency.value = 800; 
        gainNode.gain.setValueAtTime(1, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.1);
        oscillator.start(audioCtx.currentTime);
        oscillator.stop(audioCtx.currentTime + 0.1);
    }

    // PROSES SCAN (KAMERA)
    function onScanSuccess(decodedText) {
        if(isProcessing) return;
        playBeep();
        html5QrcodeScanner.pause(true); // Jeda kamera
        processPaymentAjax(decodedText);
    }
    function onScanFailure(error) { /* Abaikan error pencarian frame */ }

    // PROSES SCAN (MANUAL / ALAT SCANNER FISIK)
    function handleManualSubmit(e) {
        e.preventDefault();
        if(isProcessing) return;
        let code = document.getElementById('manual_code').value.trim().toUpperCase();
        if(code) {
            playBeep();
            if(html5QrcodeScanner && html5QrcodeScanner.getState() === Html5QrcodeScannerState.SCANNING) {
                html5QrcodeScanner.pause(true);
            }
            processPaymentAjax(code);
        }
    }

    // KIRIM DATA KE BACKEND (Controller yg udah kita buat)
    function processPaymentAjax(qrCodeValue) {
        isProcessing = true;
        let scanResultBox = document.getElementById('scanResult');
        
        fetch('{{ route("kasir.scanner.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ qr_code: qrCodeValue })
        })
        .then(response => response.json())
        .then(data => {
            scanResultBox.classList.remove('hidden');
            if(data.success) {
                scanResultBox.className = "mt-4 p-4 rounded-xl text-center font-bold text-sm text-[#182B87] bg-[#A8FF00] shadow-md";
                scanResultBox.innerText = data.message;
                
                // Kalau sukses, tutup scanner dan reload halaman buat mindahin order
                setTimeout(() => {
                    closeScannerModal();
                    window.location.reload();
                }, 1500);
            } else {
                scanResultBox.className = "mt-4 p-4 rounded-xl text-center font-bold text-sm text-white bg-red-600 shadow-md";
                scanResultBox.innerText = data.message;
                
                // Reset form buat nyoba lagi
                setTimeout(() => {
                    scanResultBox.classList.add('hidden');
                    document.getElementById('manual_code').value = '';
                    isProcessing = false;
                    if(html5QrcodeScanner && html5QrcodeScanner.getState() === Html5QrcodeScannerState.PAUSED) {
                        html5QrcodeScanner.resume();
                    }
                }, 2500);
            }
        })
        .catch(error => {
            scanResultBox.classList.remove('hidden');
            scanResultBox.className = "mt-4 p-4 rounded-xl text-center font-bold text-sm text-white bg-red-600 shadow-md";
            scanResultBox.innerText = "Error! Gagal menghubungi server.";
            isProcessing = false;
        });
    }

    // AUTO-REFRESH HALAMAN (Tiap 5 Detik)
    setInterval(() => {
        if (!isModalOpen) {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    document.getElementById('pos-board').innerHTML = doc.getElementById('pos-board').innerHTML;
                })
                .catch(err => console.error('Gagal fetch data terbaru', err));
        }
    }, 5000);
    
</script>

<style>
    /* Kustomisasi tombol bawaan HTML5 QR Code agar lebih rapi */
    #reader { border: none !important; }
    #reader button {
        background-color: #182B87; color: white; border: none; padding: 8px 16px; border-radius: 8px;
        font-size: 12px; font-weight: bold; cursor: pointer; margin-bottom: 10px;
    }
    #reader button:hover { background-color: #0d1a5c; }
    #reader__dashboard_section_csr span { color: #6b7280; font-size: 12px; }
</style>
@endsection