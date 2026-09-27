# Product Requirements Document (PRD)
**Sistem Informasi Operasional Berbasis QR-Order, POS, dan Analitik Penjualan pada Sicasa Cafe Padang**

---

## 1. Informasi Proyek
* **Nama Proyek:** Sicasa Cafe QR-Order & POS System
* **Klien:** Sicasa Cafe Padang
* **Metodologi:** Waterfall
* **Tim Pengembang:** Kelompok 4 (PM, UI/UX, Programmer Frontend/Backend, QA, Data Analyst)

## 2. Latar Belakang & Identifikasi Masalah
Sicasa Cafe memiliki kapasitas 26 meja (79 kursi) dengan 5 karyawan (3 staf *multi-role*, 2 koki). Pada jam sibuk (19.00 - 23.00 WIB), kafe mengalami beberapa kendala operasional:
1. **Bottleneck Transaksi:** Penumpukan antrean pemesanan di meja kasir.
2. **Risiko Human Error:** Penyampaian pesanan ke dapur masih manual/lisan.
3. **Beban Kerja Staf:** Karyawan *multi-role* kesulitan membagi fokus antara kasir dan pelayanan.
4. **Ketiadaan Analitik:** Pemilik kesulitan menganalisis tren penjualan, *peak hours*, dan produk *best-seller*.

## 3. Tujuan Proyek
Membangun sistem informasi terintegrasi yang mencakup:
* Aplikasi pemesanan mandiri via QR Code untuk pelanggan (tanpa *install* aplikasi).
* Modul Point of Sale (POS) Kasir untuk verifikasi pembayaran (skema *Pay-First*) dan pencetakan 2 struk (Struk Pelanggan & Struk Dapur).
* Dashboard Analytics khusus Owner untuk memantau data transaksi *real-time*.

## 4. Batas Lingkup Sistem (Scope of Work)
**In-Scope:**
* QR-Order Customer (Katalog, Kustomisasi Menu, Keranjang, Checkout).
* POS Kasir (Verifikasi Pembayaran QRIS/Tunai, Manajemen Status Meja & Stok, Pencetakan Struk).
* Dashboard Owner (Omzet, Jumlah Transaksi, *Peak Hours*, *Best/Low Performing Menu*).

**Out-of-Scope:**
* Integrasi pihak ketiga (GoFood, GrabFood, dll).
* Aplikasi *Mobile Native* (Android/iOS).
* Sistem Akuntansi/Penggajian & Modul *Loyalty/Membership*.

## 5. Kebutuhan Fungsional Utama (Fitur)
### Modul Customer (Mobile Web)
* Pemindaian QR Code meja untuk deteksi otomatis nomor meja.
* Form input nama pelanggan.
* Katalog menu beserta kustomisasi (contoh: Hot/Cold).
* Keranjang pesanan dan *checkout* metode pembayaran (Tunai/QRIS).
* Pemantauan status pesanan (menunggu verifikasi, diproses, selesai).

### Modul Kasir (POS)
* Autentikasi (Login).
* Notifikasi *real-time* pesanan masuk dari pelanggan.
* Verifikasi pembayaran manual.
* *Trigger* cetak struk otomatis (Dapur & Customer) via printer thermal.
* *Toggle* ketersediaan stok menu (Habis/Tersedia).

### Modul Owner (Dashboard)
* Autentikasi (Login).
* Visualisasi metrik bisnis utama: Omzet harian/bulanan, Rata-rata transaksi, *Best Seller*.

## 6. Alur Kerja (Workflow) - Skema Pay-First
1. **Order:** Customer memindai QR -> Pilih Menu -> Checkout -> Kirim Pesanan.
2. **Verify:** Pesanan masuk ke POS Kasir -> Kasir memverifikasi pembayaran fisik/QRIS.
3. **Print & Process:** Sistem mencatat transaksi -> Printer mencetak Struk Customer & Struk Dapur -> Dapur menyiapkan pesanan.
4. **Analyze:** Data transaksi tersimpan -> Tampil di Dashboard Analytics Owner.

## 7. Technology Stack
* **Backend:** Laravel
* **Frontend Customer:** Mobile Web (Blade / HTML5, CSS3, JS)
* **Frontend POS:** Flutter / Web POS
* **Database:** MySQL / PostgreSQL
* **Hardware Integration:** Thermal Printer (ESC/POS) via USB/LAN/Bluetooth