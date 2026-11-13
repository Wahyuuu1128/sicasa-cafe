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

        $totalPesanan = Order::whereDate('created_at', $today)->count();
        // PERBAIKAN 1: Ganti jadi 'menunggu_bayar'
        $menunggu     = Order::where('status_pesanan', 'menunggu_bayar')->count();
        $diproses     = Order::where('status_pesanan', 'diproses')->count();
        $selesai      = Order::where('status_pesanan', 'selesai')->whereDate('updated_at', $today)->count();

        $riwayatTerbaru = Order::with('table')
            ->where('status_pesanan', 'selesai')
            ->whereDate('updated_at', $today)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact('totalPesanan', 'menunggu', 'diproses', 'selesai', 'riwayatTerbaru'));
    }

    // ==========================================
    // 2. PESANAN MASUK (DINE-IN QR)
    // ==========================================
    public function index()
    {
        // PERBAIKAN 2: Ganti jadi 'menunggu_bayar'
        $pesananBaru = Order::with(['table', 'orderDetails.menu'])
            ->where('status_pesanan', 'menunggu_bayar') 
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
        $categories = Category::all();

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
        $qr_code = $request->qr_code; 
        $order_id = str_replace('SICASA-', '', $qr_code);
        $order = \App\Models\Order::find($order_id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Barcode tidak valid! Pesanan tidak ditemukan.']);
        }

        // PERBAIKAN: Cek apakah metode bayar itu cash atau tunai (case-insensitive)
        $payment_method = strtolower($order->payment);
        if ($payment_method != 'tunai' && $payment_method != 'cash') {
            return response()->json(['success' => false, 'message' => 'Pesanan ini pakai metode ' . $order->payment . ', bukan Cash.']);
        }

        if ($order->status_pesanan == 'diproses' || $order->status_pesanan == 'selesai') {
            return response()->json(['success' => false, 'message' => 'Pesanan ini sudah dibayar sebelumnya.']);
        }

        $order->status_pesanan = 'diproses';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran Cash Rp' . number_format($order->total_harga, 0, ',', '.') . ' untuk Meja ' . str_replace('Meja ', '', $order->table->nomor_meja ?? '-') . ' Berhasil!',
            'order_id' => $order->id
        ]);
    }
}