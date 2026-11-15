<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\Category;
use Carbon\Carbon;

class PosController extends Controller
{
    // ==========================================
    // 1. DASHBOARD KASIR
    // ==========================================
    public function dashboard()
    {
        $today = Carbon::today();

        $totalPendapatan = Order::where('status_pesanan', 'selesai')->whereDate('updated_at', $today)->sum('total_harga');
        $kasTunai        = Order::where('status_pesanan', 'selesai')->whereDate('updated_at', $today)->whereIn('payment', ['cash', 'tunai', 'Tunai', 'Cash'])->sum('total_harga');
        $menunggu        = Order::where('status_pesanan', 'menunggu_bayar')->whereIn('payment', ['cash', 'tunai', 'Tunai', 'Cash'])->count();
        $selesai         = Order::where('status_pesanan', 'selesai')->whereDate('updated_at', $today)->count();

        $riwayatTerbaru = Order::with('table')
            ->where('status_pesanan', 'selesai')
            ->whereDate('updated_at', $today)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact('totalPendapatan', 'kasTunai', 'menunggu', 'selesai', 'riwayatTerbaru'));
    }

    // ==========================================
    // 2. PESANAN MASUK (DINE-IN QR)
    // ==========================================
    public function index()
    {
        // Hanya pesanan Tunai/Cash yang butuh verifikasi manual kasir di kolom 'Menunggu Verifikasi'.
        // Pesanan QRIS yang belum dibayar tidak membebani kasir, dan otomatis masuk ke kolom 'Diproses' begitu lunas!
        $pesananBaru = Order::with(['table', 'orderDetails.menu'])
            ->where('status_pesanan', 'menunggu_bayar') 
            ->whereIn('payment', ['cash', 'tunai', 'Tunai', 'Cash'])
            ->orderBy('created_at', 'asc')
            ->get();

        $diproses = Order::with(['table', 'orderDetails.menu'])
            ->where('status_pesanan', 'diproses')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('kasir.pesanan', compact('pesananBaru', 'diproses'));
    }

    // ==========================================
    // 3. MESIN POS (TAKE-AWAY)
    // ==========================================
    public function takeaway()
    {
        // Hanya tampilkan menu yang tersedia
        $menus = Menu::with('kategori')->where('is_available', true)->get();
        $categories = Category::whereHas('menus', function($query) {
            $query->where('is_available', true);
        })->get();

        return view('kasir.takeaway', compact('menus', 'categories'));
    }

    public function checkoutTakeaway(Request $request)
    {
        $request->validate([
            'total_harga'       => 'required|numeric',
            'items'             => 'required|json',
            'metode_pembayaran' => 'required',
        ]);

        // Simpan Pesanan Utama (Tanpa Meja)
        $order = Order::create([
            'table_id'       => null, 
            'nama_pemesan'   => $request->nama_pemesan ?: 'Take-away (Kasir)',
            'total_harga'    => $request->total_harga,
            'status_pesanan' => 'selesai',
            'payment'        => $request->metode_pembayaran,
        ]);

        // Simpan Detail Item yang Dipesan
        $items = json_decode($request->items, true);
        foreach($items as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id'  => $item['id'],
                'jumlah'   => $item['qty'],
                'harga'    => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'] 
            ]);
        }

        return redirect()->route('kasir.pos')->with('sukses_bayar', $order->id);
    }

    // ==========================================
    // 4. RIWAYAT TRANSAKSI & CETAK STRUK
    // ==========================================
    public function history()
    {
        $riwayat = Order::with('table')
            ->where('status_pesanan', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('kasir.history', compact('riwayat'));
    }

    public function export()
    {
        $orders = Order::with(['table', 'orderDetails.menu'])
            ->where('status_pesanan', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->get();

        $filename = "Laporan_Transaksi_Sicasa_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM agar Microsoft Excel Windows langsung membaca format & karakter dengan rapi
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Metadata / Header Laporan
            fputcsv($file, ['LAPORAN TRANSAKSI PENJUALAN - SICASA SOCIAL SPACE PADANG'], ';');
            fputcsv($file, ['Waktu Unduh', now()->translatedFormat('d F Y, H:i') . ' WIB'], ';');
            fputcsv($file, ['Dicetak Oleh', (auth()->user()->name ?? 'Kasir') . ' (' . strtoupper(auth()->user()->role ?? 'KASIR') . ')'], ';');
            fputcsv($file, ['Status Pesanan', 'Semua Pesanan Selesai (Lunas & Terlayani)'], ';');
            fputcsv($file, [], ';');

            // Header Kolom Tabel
            $columns = [
                'No',
                'No. Order',
                'Waktu Selesai (WIB)',
                'Tipe Pesanan',
                'No. Meja',
                'Nama Pelanggan',
                'Metode Pembayaran',
                'Rincian Menu (Item x Qty)',
                'Total Tagihan (Rp)',
                'Status'
            ];
            fputcsv($file, $columns, ';');

            $totalOmzet = 0;
            $totalTunai = 0;
            $totalQris  = 0;
            $no = 1;

            foreach ($orders as $order) {
                $isTakeaway = empty($order->table_id);
                $tipe = $isTakeaway ? 'Take-away' : 'Dine-in';
                $meja = $isTakeaway ? '-' : 'Meja ' . ($order->table->nomor_meja ?? '-');
                $pelanggan = $order->nama_pemesan ?: ($isTakeaway ? 'Pelanggan Kasir' : '-');
                
                $metode = strtoupper($order->payment ?? 'TUNAI');
                if (in_array($metode, ['CASH', 'TUNAI'])) {
                    $metodeLabel = 'Tunai (Kasir)';
                    $totalTunai += $order->total_harga;
                } else {
                    $metodeLabel = 'QRIS';
                    $totalQris += $order->total_harga;
                }
                $totalOmzet += $order->total_harga;

                $rincianArr = [];
                if ($order->orderDetails) {
                    foreach ($order->orderDetails as $detail) {
                        $menuNama = $detail->menu->nama_menu ?? 'Item Dihapus';
                        $rincianArr[] = "{$menuNama} [{$detail->jumlah}x]";
                    }
                }
                $rincianStr = count($rincianArr) > 0 ? implode(', ', $rincianArr) : '-';

                fputcsv($file, [
                    $no++,
                    'ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    $order->updated_at->format('d/m/Y H:i'),
                    $tipe,
                    $meja,
                    $pelanggan,
                    $metodeLabel,
                    $rincianStr,
                    $order->total_harga,
                    'SELESAI'
                ], ';');
            }

            // Summary / Rekapitulasi di bagian bawah
            fputcsv($file, [], ';');
            fputcsv($file, ['RINGKASAN OPERASIONAL TRANSAKSI'], ';');
            fputcsv($file, ['Total Transaksi Selesai', count($orders) . ' Pesanan'], ';');
            fputcsv($file, ['Total Uang Masuk Kas Laci (Tunai)', 'Rp ' . number_format($totalTunai, 0, ',', '.')], ';');
            fputcsv($file, ['Total Uang Masuk QRIS', 'Rp ' . number_format($totalQris, 0, ',', '.')], ';');
            fputcsv($file, ['TOTAL PENDAPATAN (OMZET KESELURUHAN)', 'Rp ' . number_format($totalOmzet, 0, ',', '.')], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printStruk($order_id)
    {
        // Tarik data pesanan lengkap beserta relasi meja dan detail menu
        $order = Order::with(['table', 'orderDetails.menu'])->findOrFail($order_id);
        
        return view('kasir.struk', compact('order'));
    }

    // ==========================================
    // 5. AKSI CEPAT (VERIFIKASI & STOK)
    // ==========================================
    public function verifyPayment($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->update(['status_pesanan' => 'diproses']);
        
        return redirect()->back()->with('success', 'Pembayaran diverifikasi, pesanan masuk ke antrean dapur!');
    }

    public function toggleStok($menu_id)
    {
        $menu = Menu::findOrFail($menu_id);
        $menu->update(['is_available' => !$menu->is_available]);
        
        return redirect()->back()->with('success', 'Status stok menu berhasil diperbarui!');
    }

    public function completeOrder($order_id)
    {
        $order = Order::findOrFail($order_id);
        
        // Ubah status jadi 'selesai'
        $order->update(['status_pesanan' => 'selesai']);
        
        return redirect()->back()->with('success', 'Pesanan selesai dan masuk ke Laporan Owner!');
    }

    // ==========================================
    // FUNGSI SCANNER BARCODE (PEMBAYARAN TUNAI)
    // ==========================================
    public function scanner()
    {
        return view('kasir.scanner');
    }

    public function processScanner(\Illuminate\Http\Request $request)
    {
        $qr_code = trim($request->qr_code ?? ''); 
        $order_id = preg_replace('/[^0-9]/', '', $qr_code);

        if (!$order_id) {
            return response()->json(['success' => false, 'message' => 'Format kode pembayaran tidak valid.']);
        }

        $order = \App\Models\Order::with('table')->find($order_id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan #' . $order_id . ' tidak ditemukan!']);
        }

        // PERBAIKAN: Cek apakah metode bayar itu cash atau tunai (case-insensitive)
        $payment_method = strtolower($order->payment ?? '');
        if ($payment_method != 'tunai' && $payment_method != 'cash') {
            return response()->json(['success' => false, 'message' => 'Pesanan ini pakai metode ' . strtoupper($order->payment) . ', bukan Cash/Tunai.']);
        }

        if ($order->status_pesanan == 'diproses' || $order->status_pesanan == 'selesai') {
            return response()->json(['success' => false, 'message' => 'Pesanan #' . $order->id . ' sudah berstatus ' . strtoupper($order->status_pesanan) . '.']);
        }

        $order->status_pesanan = 'diproses';
        $order->save();

        $meja = $order->table ? str_replace('Meja ', '', $order->table->nomor_meja) : '-';

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran Cash Rp' . number_format($order->total_harga, 0, ',', '.') . ' untuk Meja ' . $meja . ' Berhasil!',
            'order_id' => $order->id
        ]);
    }
}