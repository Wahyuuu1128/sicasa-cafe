@extends('layouts.kasir')
@section('title', 'Scanner Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-[#182B87] tracking-tight">Scanner Kasir</h1>
        <p class="text-gray-500 mt-2">Scan Barcode / QR Code dari HP pelanggan untuk pesanan Tunai.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI: AREA KAMERA SCANNER -->
        <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden relative">
            <div class="bg-[#17191C] text-white px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold tracking-wider text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A8FF00" stroke-width="2"><path d="M4 7V4h3"></path><path d="M20 7V4h-3"></path><path d="M4 17v3h3"></path><path d="M20 17v3h-3"></path><line x1="12" y1="9" x2="12" y2="15"></line></svg>
                    KAMERA AKTIF
                </h3>
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            </div>
            
            <div class="p-4 bg-gray-50">
                <!-- Elemen HTML5 QR Code -->
                <div id="reader" class="w-full rounded-xl overflow-hidden border-2 border-dashed border-gray-300"></div>
            </div>
        </div>

        <!-- KOLOM KANAN: INPUT MANUAL & LOG HASIL -->
        <div class="flex flex-col gap-6">
            
            <!-- Form Input Manual -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-[#182B87] mb-4 text-sm uppercase">Input Manual (Fisik)</h3>
                <p class="text-xs text-gray-500 mb-4">Gunakan ini jika kamera rusak atau Anda menggunakan alat scanner tembak (barcode gun).</p>
                
                <form id="manualForm" onsubmit="handleManualSubmit(event)">
                    <div class="relative">
                        <input type="text" id="manual_code" placeholder="Cth: SICASA-44" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#182B87] focus:border-[#182B87] block p-3 pr-10 uppercase font-bold" required autocomplete="off">
                        <svg class="absolute right-3 top-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    </div>
                    <button type="submit" class="w-full mt-3 bg-[#A8FF00] hover:bg-[#95e600] text-[#182B87] font-bold py-3 rounded-lg transition-colors">
                        Proses Pembayaran
                    </button>
                </form>
            </div>

            <!-- Log Pesan -->
            <div id="resultBox" class="hidden bg-[#182B87] text-white rounded-2xl shadow-lg p-6 relative overflow-hidden transition-all">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                <div id="resultIcon" class="mb-4"></div>
                <h4 id="resultTitle" class="text-xl font-bold mb-2">Sukses!</h4>
                <p id="resultText" class="text-sm text-blue-100 font-medium"></p>
                <button onclick="resetScanner()" class="mt-6 w-full bg-white/20 hover:bg-white/30 text-white font-bold py-2 rounded-lg text-sm transition-colors">
                    Scan Selanjutnya
                </button>
            </div>

        </div>
    </div>
</div>

<!-- LIBRARY HTML5 QRCODE -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrcodeScanner;
    let isProcessing = false;

    // Suara Beep Kasir (Sintesis Audio Browser)
    function playBeep() {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        oscillator.type = 'sine';
        oscillator.frequency.value = 800; // Nada tinggi
        gainNode.gain.setValueAtTime(1, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.1);
        oscillator.start(audioCtx.currentTime);
        oscillator.stop(audioCtx.currentTime + 0.1);
    }

    // Inisialisasi Scanner Kamera
    function initScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
            fps: 10, 
            qrbox: {width: 250, height: 250},
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        }, false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    // Saat Kamera Berhasil Baca Barcode
    function onScanSuccess(decodedText, decodedResult) {
        if(isProcessing) return; // Cegah scan dobel
        
        playBeep(); // Bunyikan Beep
        html5QrcodeScanner.pause(true); // Jeda kamera sebentar
        processPayment(decodedText);
    }

    function onScanFailure(error) {
        // Abaikan error saat proses nyari barcode (normal)
    }

    // Saat Kasir Pakai Input Manual/Scanner Tembak
    function handleManualSubmit(e) {
        e.preventDefault();
        if(isProcessing) return;

        let code = document.getElementById('manual_code').value.trim().toUpperCase();
        if(code) {
            playBeep();
            if(html5QrcodeScanner) html5QrcodeScanner.pause(true);
            processPayment(code);
        }
    }

    // Tembak Data via AJAX ke Controller
    function processPayment(qrCodeValue) {
        isProcessing = true;
        
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
            showResult(data.success, data.message);
        })
        .catch(error => {
            showResult(false, 'Terjadi kesalahan koneksi server.');
        });
    }

    // Menampilkan Pesan Hasil Scan
    function showResult(isSuccess, message) {
        const box = document.getElementById('resultBox');
        const icon = document.getElementById('resultIcon');
        const title = document.getElementById('resultTitle');
        const text = document.getElementById('resultText');

        box.classList.remove('hidden');
        document.getElementById('manual_code').value = '';

        if(isSuccess) {
            box.className = "bg-[#182B87] text-white rounded-2xl shadow-lg p-6 relative overflow-hidden transition-all";
            icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#A8FF00" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
            title.innerText = "Pembayaran Sukses!";
            text.innerText = message;
        } else {
            box.className = "bg-red-600 text-white rounded-2xl shadow-lg p-6 relative overflow-hidden transition-all";
            icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`;
            title.innerText = "Gagal Divalidasi!";
            text.innerText = message;
        }
    }

    // Reset untuk Scan Pelanggan Berikutnya
    function resetScanner() {
        document.getElementById('resultBox').classList.add('hidden');
        isProcessing = false;
        
        if(html5QrcodeScanner) {
            html5QrcodeScanner.resume(); // Nyalakan lagi kameranya
        }
        document.getElementById('manual_code').focus();
    }

    // Jalankan scanner saat halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        initScanner();
        document.getElementById('manual_code').focus(); // Otomatis fokus ke input kalau pakai scanner fisik
    });
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