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
        $menunggu     = Order::where('status_pesanan', 'menunggu_pembayaran')->count();
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
        $pesananBaru = Order::with(['table', 'orderDetails.menu'])
            ->where('status_pesanan', 'menunggu_pembayaran')
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
                'subtotal' => $item['qty'] * $item['harga'] // <-- TAMBAHKAN BARIS INI BRO!
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
}