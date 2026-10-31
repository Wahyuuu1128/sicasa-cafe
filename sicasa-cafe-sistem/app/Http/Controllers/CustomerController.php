<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderDetail;

class CustomerController extends Controller
{
    // 1. Halaman Awal Scan QR
    public function index($table_id)
    {
        $table = Table::findOrFail($table_id);
        return view('customer.index', compact('table'));
    }

    // 2. Simpan Nama Pelanggan ke Session
    public function setNama(Request $request, $table_id)
    {
        $request->validate(['nama_pemesan' => 'required|string|max:50']);
        session(['nama_pemesan' => $request->nama_pemesan]);
        
        return redirect()->route('customer.menu', $table_id);
    }

    // 3. Halaman Katalog Menu
    public function menu($table_id)
    {
        if (!session()->has('nama_pemesan')) {
            return redirect()->route('customer.index', $table_id);
        }

        $table = Table::findOrFail($table_id);
        $menus = Menu::where('is_available', true)->get();
        $categories = Category::all();

        return view('customer.menu', compact('table', 'menus', 'categories'));
    }

    // 4. Proses Checkout & Generate Midtrans Snap
    public function checkout(Request $request, $table_id)
    {
        try {
            $items = json_decode($request->items, true);

            // ==========================================================
            // JURUS BYPASS: HARDCODE SERVER KEY (DIJAMIN KEBACA!)
            // ==========================================================
            \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // 1. Buat Order di Database
            $order = Order::create([
                'table_id'       => $table_id,
                'nama_pemesan'   => session('nama_pemesan') ?? 'Pelanggan',
                'total_harga'    => $request->total_harga,
                'status_pesanan' => 'menunggu_bayar', 
                'payment'        => 'QRIS', 
            ]);

            // 2. Simpan Detail Order
            $itemDetails = [];
            foreach($items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id'  => $item['id'],
                    'jumlah'   => $item['qty'],
                    'harga'    => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga']
                ]);

                $itemDetails[] = [
                    'id'       => $item['id'],
                    'price'    => $item['harga'],
                    'quantity' => $item['qty'],
                    'name'     => substr($item['nama'], 0, 50)
                ];
            }

            // 3. Request Token ke Midtrans
            $params = [
                'transaction_details' => [
                    'order_id'     => 'SICASA-' . $order->id . '-' . time(),
                    'gross_amount' => $order->total_harga,
                ],
                'customer_details' => [
                    'first_name' => session('nama_pemesan') ?? 'Pelanggan',
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // 4. Update Token ke Database
            $order->update(['snap_token' => $snapToken]);
            
            return response()->json(['token' => $snapToken, 'order_id' => $order->id]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 5. Halaman Status Lacak Pesanan
    public function status($order_id)
    {
        $order = Order::with('orderDetails.menu', 'table')->findOrFail($order_id);
        return view('customer.status', compact('order'));
    }

    // 6. Webhook Callback dari Midtrans
    public function callback(Request $request)
    {
        // Taruh Server Key lu disini juga nanti biar webhooknya jalan
        $serverKey = 'PASTE_SERVER_KEY_LU_DISINI'; 
        
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $orderId = explode('-', $request->order_id)[1];
                $order = Order::find($orderId);
                
                if ($order) {
                    $order->update(['status_pesanan' => 'diproses']);
                }
            }
        }
        return response()->json(['message' => 'Callback handled']);
    }
}