<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'this_month');
        $query = Order::where('status_pesanan', 'selesai'); 

        // VARIABEL UNTUK GRAFIK CHART.JS
        $chartLabels = [];
        $chartData = [];

        if ($filter == 'today') {
            $query->whereDate('updated_at', Carbon::today());
            $labelWaktu = 'Hari Ini';
            
            // Data Grafik Jam-jaman (Buka dari jam 08:00 - 23:00)
            $sales = Order::where('status_pesanan', 'selesai')->whereDate('updated_at', Carbon::today())
                ->select(DB::raw('HOUR(updated_at) as hour'), DB::raw('SUM(total_harga) as total'))
                ->groupBy('hour')->pluck('total', 'hour')->toArray();
            for ($i = 8; $i <= 23; $i++) {
                $chartLabels[] = $i . ':00';
                $chartData[] = $sales[$i] ?? 0;
            }
        } elseif ($filter == 'this_week') {
            $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $labelWaktu = 'Minggu Ini';
            
            // Data Grafik 7 Hari
            $start = Carbon::now()->startOfWeek();
            $namaHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $chartLabels[] = $namaHari[$i];
                $chartData[] = Order::where('status_pesanan', 'selesai')->whereDate('updated_at', $date->toDateString())->sum('total_harga');
            }
        } else {
            $query->whereMonth('updated_at', Carbon::now()->month)->whereYear('updated_at', Carbon::now()->year);
            $labelWaktu = 'Bulan Ini';
            
            // Data Grafik per Tanggal (1-31)
            $start = Carbon::now()->startOfMonth();
            $days = Carbon::now()->daysInMonth;
            for ($i = 1; $i <= $days; $i++) {
                $date = $start->copy()->addDays($i - 1);
                $chartLabels[] = 'Tgl '.$i;
                $chartData[] = Order::where('status_pesanan', 'selesai')->whereDate('updated_at', $date->toDateString())->sum('total_harga');
            }
        }

        $orders = $query->get();
        $totalOmzet = $orders->sum('total_harga');
        $totalTransaksi = $orders->count();
        $omzetTunai = $orders->where('payment', 'Tunai')->sum('total_harga');
        $omzetQris  = $orders->where('payment', 'QRIS')->sum('total_harga');

        $bestSellers = OrderDetail::whereHas('order', function($q) use ($filter) {
                $q->where('status_pesanan', 'selesai');
                if ($filter == 'today') { $q->whereDate('updated_at', Carbon::today()); }
                elseif ($filter == 'this_week') { $q->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); }
                else { $q->whereMonth('updated_at', Carbon::now()->month)->whereYear('updated_at', Carbon::now()->year); }
            })
            ->select('menu_id', DB::raw('SUM(jumlah) as total_terjual')) 
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->with('menu')->get();

        return view('owner.dashboard', compact(
            'totalOmzet', 'totalTransaksi', 'omzetTunai', 'omzetQris', 'bestSellers', 'filter', 'labelWaktu', 'chartLabels', 'chartData'
        ));
    }

    public function history(Request $request)
    {
        $filter = $request->query('filter', 'semua');
        $query = Order::with(['table', 'orderDetails.menu'])->where('status_pesanan', 'selesai');

        if ($filter == 'today') { $query->whereDate('updated_at', Carbon::today()); }
        elseif ($filter == 'this_week') { $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); }
        elseif ($filter == 'this_month') { $query->whereMonth('updated_at', Carbon::now()->month)->whereYear('updated_at', Carbon::now()->year); }

        $orders = $query->orderBy('updated_at', 'desc')->get();
        return view('owner.history', compact('orders', 'filter'));
    }

    // FUNGSI NATIVE CSV EXPORT (Langsung buka di Excel)
    public function export(Request $request)
    {
        $orders = Order::with(['table', 'orderDetails.menu'])->where('status_pesanan', 'selesai')->orderBy('updated_at', 'desc')->get();
        
        $filename = "Laporan_Sicasa_" . date('Y-m-d_H-i') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['ID Pesanan', 'Tanggal', 'Tipe Pesanan', 'Nama Pelanggan', 'Metode Bayar', 'Total Tagihan (Rp)', 'Rincian Menu (Qty)'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            // Pakai delimiter titik koma (;) agar rapi di Microsoft Excel versi Indonesia
            fputcsv($file, $columns, ';');

            foreach ($orders as $order) {
                $tipe = $order->table_id ? 'Meja ' . $order->table->nomor_meja : 'Take-away';
                $rincian = [];
                foreach($order->orderDetails as $detail) {
                    $rincian[] = ($detail->menu->nama_menu ?? 'Terhapus') . " (" . $detail->jumlah . "x)";
                }
                
                fputcsv($file, [
                    '#' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    $order->updated_at->format('Y-m-d H:i'),
                    $tipe,
                    $order->nama_pemesan ?? '-',
                    $order->payment ?? 'Tunai',
                    $order->total_harga,
                    implode(', ', $rincian)
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}