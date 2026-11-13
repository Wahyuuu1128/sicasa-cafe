<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;

class CustomerController extends Controller
{
    // ==========================================
    // 1. HALAMAN AWAL (INPUT MANUAL)
    // ==========================================
    public function landing()
    {
        return view('customer.landing');
    }

    public function mulaiPesanan(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:50',
            'nomor_meja'   => 'required|numeric|min:1|max:25'
        ]);

        $table = Table::firstOrCreate(['nomor_meja' => $request->nomor_meja]);

        session([
            'nama_pemesan' => $request->nama_pemesan,
            'table_id'     => $table->id,
            'nomor_meja'   => $request->nomor_meja
        ]);

        return redirect()->route('customer.menu');
    }

    // ==========================================
    // 2. HALAMAN MENU
    // ==========================================
    public function menu()
    {
        if (!session()->has('table_id') || !session()->has('nama_pemesan')) {
            return redirect()->route('customer.landing')->with('error', 'Silakan isi data terlebih dahulu.');
        }

        $table = Table::find(session('table_id'));
        $nama_pemesan = session('nama_pemesan');
        
        $menus = Menu::with('kategori')->where('is_available', true)->get();
        $categories = Category::with(['menus' => function($query) {
            $query->where('is_available', true);
        }])->get();

        return view('customer.menu', compact('table', 'nama_pemesan', 'categories', 'menus'));
    }

    // ==========================================
    // 3. HALAMAN CHECKOUT
    // ==========================================
    public function checkout()
    {
        if (!session()->has('table_id') || !session()->has('nama_pemesan')) {
            return redirect()->route('customer.landing')->with('error', 'Silakan isi data meja terlebih dahulu.');
        }

        $nama_pemesan = session('nama_pemesan');
        $nomor_meja = session('nomor_meja');

        return view('customer.checkout', compact('nama_pemesan', 'nomor_meja'));
    }

    // ==========================================
    // 4. PROSES PEMBAYARAN & MIDTRANS API
    // ==========================================
    public function processPayment(Request $request)
    {
        $request->validate([
            'nama_pemesan'   => 'required|string|max:50',
            'nomor_meja'     => 'required|numeric|min:1|max:25',
            'payment_method' => 'required|in:cash,qris',
            'cart_data'      => 'required|json'
        ]);

        $cart = json_decode($request->cart_data, true);
        if (count($cart) == 0) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // Update session jika pelanggan mengubah nama/meja di halaman checkout
        $table = Table::firstOrCreate(['nomor_meja' => $request->nomor_meja]);
        session([
            'nama_pemesan' => $request->nama_pemesan,
            'table_id'     => $table->id,
            'nomor_meja'   => $request->nomor_meja
        ]);

        $total_harga = 0;
        foreach ($cart as $item) {
            $total_harga += ($item['harga'] * $item['qty']);
        }

        // Simpan ke Database
        $order = Order::create([
            'table_id'       => $table->id,
            'nama_pemesan'   => $request->nama_pemesan,
            'total_harga'    => $total_harga,
            'status_pesanan' => 'menunggu_bayar',
            'payment'        => $request->payment_method,
        ]);

        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id'  => $item['id'],
                'jumlah'   => $item['qty'],
                'harga'    => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga']
            ]);
        }

        // Jika Bayar Tunai
        if ($request->payment_method === 'cash') {
            return redirect()->route('customer.cash.show', $order->id);
        } 
        
        // Jika Bayar QRIS (Gunakan Midtrans Core API)
        else {
            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;
            
            // Bypass SSL Error di Localhost/PHP8
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => []
            ];

            // Setup Parameter Core API
            $order_code = 'SICASA-' . $order->id . '-' . time();
            $params = [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $order_code,
                    'gross_amount' => $total_harga,
                ],
                'customer_details' => [
                    'first_name' => $request->nama_pemesan,
                ],
            ];

            try {
                // Panggil Core API Midtrans
                $response = \Midtrans\CoreApi::charge($params);
                
                // Ekstrak URL Gambar QR Code mentah dari response Midtrans
                $qr_url = null;
                if (isset($response->actions)) {
                    foreach ($response->actions as $action) {
                        if ($action->name === 'generate-qr-code') {
                            $qr_url = $action->url;
                            break;
                        }
                    }
                }

                if (!$qr_url) {
                    throw new Exception("Gagal mendapatkan QRIS dari Midtrans.");
                }

                // Simpan URL QR sementara di session biar bisa ditampilin di halaman berikutnya
                session()->put('qris_url_' . $order->id, $qr_url);
                
                return redirect()->route('customer.qris.show', $order->id);

            } catch (Exception $e) {
                // Jika error, hapus pesanan atau ubah status jadi gagal (bisa disesuaikan)
                return redirect()->back()->with('error', 'Midtrans Error: ' . $e->getMessage());
            }
        }
    }

    // ==========================================
    // 5. TAMPILKAN CUSTOM QRIS PAGE
    // ==========================================
    public function showQris($order_id)
    {
        $order = Order::with('table')->findOrFail($order_id);
        
        // Ambil URL QRIS dari session yang disimpan saat proses pembayaran
        $qr_url = session('qris_url_' . $order_id);

        if (!$qr_url && $order->status_pesanan == 'menunggu_bayar') {
            return redirect()->route('customer.landing')->with('error', 'Sesi QRIS kadaluarsa.');
        }

        return view('customer.qris', compact('order', 'qr_url'));
    }

    // ==========================================
    // 6. TAMPILKAN BARCODE KASIR (BAYAR CASH)
    // ==========================================
    public function showCash($order_id)
    {
        $order = Order::with('table')->findOrFail($order_id);
        
        return view('customer.cash', compact('order'));
    }

    // ==========================================
    // 7. WEBHOOK / CALLBACK DARI MIDTRANS
    // ==========================================
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        // Verifikasi keaslian notifikasi dari Midtrans
        if ($hashed == $request->signature_key) {
            // Pecah ID SICASA-{ID}-{TIME} buat dapetin ID Order aslinya
            $order_id_raw = explode('-', $request->order_id);
            $id = $order_id_raw[1]; 
            
            $order = \App\Models\Order::find($id);
            if ($order) {
                if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
                    $order->update(['status_pesanan' => 'diproses']);
                } else if (in_array($request->transaction_status, ['cancel', 'deny', 'expire'])) {
                    $order->update(['status_pesanan' => 'dibatalkan']);
                }
            }
        }
        return response()->json(['message' => 'Callback received']);
    }

    // ==========================================
    // 8. CEK STATUS TRANSAKSI (AJAX POLLING)
    // ==========================================
    public function checkStatus($order_id)
    {
        $order = \App\Models\Order::find($order_id);
        return response()->json(['status' => $order->status_pesanan ?? 'unknown']);
    }

    // ==========================================
    // 9. HALAMAN NOTA DIGITAL
    // ==========================================
    public function nota($order_id)
    {
        $order = \App\Models\Order::with(['table', 'orderDetails.menu'])->findOrFail($order_id);
        
        // Hapus session QRIS karena sudah lunas
        session()->forget('qris_url_' . $order_id);

        return view('customer.nota', compact('order'));
    }
}