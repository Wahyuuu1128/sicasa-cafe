<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} - SICASA</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            background-color: #e5e7eb;
            font-size: 12px;
            line-height: 1.4;
        }
        .receipt-container {
            width: 70mm;
            margin: 20px auto;
            background: #fff;
            padding: 5mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top; }
        
        .action-buttons {
            text-align: center;
            margin: 20px 0;
        }
        .btn {
            padding: 8px 16px;
            cursor: pointer;
            font-family: sans-serif;
            font-weight: bold;
            color: #fff;
            border: none;
            border-radius: 6px;
            margin: 0 5px;
        }
        .btn-print { background: #000; }
        .btn-back { background: #6b7280; }

        @media print {
            body { background: #fff; }
            .receipt-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
                width: 100%;
            }
            .action-buttons { display: none; }
        }
    </style>
</head>
<body>

    <div class="action-buttons">
        <button class="btn btn-print" onclick="window.print()">🖨️ Cetak Struk</button>
        <button class="btn btn-back" onclick="window.close()">Kembali ke POS</button>
    </div>

    <div class="receipt-container">
        
        <!-- Header: Logo & Info Cafe -->
        <div class="text-center">
            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 5px;">
                <!-- Filter grayscale agar logo dicetak hitam putih sempurna di thermal printer -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo Sicasa" style="width: 32px; height: auto; filter: grayscale(100%);">
                <h2 style="margin: 0; font-size: 18px; font-family: sans-serif; letter-spacing: 1px;">sicasa.pdg</h2>
            </div>
            <p style="margin: 5px 0 0; font-size: 10px;">
                Jl. Belibis Ujung No. 5, Air Tawar Barat<br>
                Kec. Padang Utara, Kota Padang<br>
                Sumatera Barat<br>
                Telp: +62 813-6576-2670
            </p>
        </div>
        
        <div class="dashed-line"></div>

        <table style="font-size: 11px;">
            <tr>
                <td>No. Order</td>
                <td class="text-right">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="text-right">{{ $order->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Tipe</td>
                <td class="text-right">{{ $order->table_id ? 'Dine-in (Meja ' . $order->table->nomor_meja . ')' : 'Take-away' }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td class="text-right">{{ $order->nama_pemesan ?? 'Take-away' }}</td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <!-- Detail Pembelian (Bug jumlah & subtotal diperbaiki) -->
        <table>
            @forelse($order->orderDetails as $detail)
                <tr>
                    <td colspan="3" class="font-bold">{{ $detail->menu->nama_menu ?? 'Menu Terhapus' }}</td>
                </tr>
                <tr>
                    <td style="width: 15%;">{{ $detail->jumlah }}x</td>
                    <td style="width: 40%;">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td style="width: 45%;" class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center italic" style="font-size: 10px;">(Tidak ada rincian item)</td>
                </tr>
            @endforelse
        </table>

        <div class="dashed-line"></div>

        <table>
            <tr>
                <td class="font-bold">TOTAL TAGIHAN</td>
                <td class="text-right font-bold" style="font-size: 14px;">
                    Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Metode Bayar</td>
                <td class="text-right">{{ $order->payment ?? 'Tunai' }}</td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <div class="text-center" style="font-size: 10px; margin-top: 15px;">
            <p style="margin: 0; font-weight: bold;">Terima Kasih atas kunjungan Anda!</p>
            <p style="margin: 4px 0;">Kritik & Saran via IG: @sicasa.pdg</p>
            <p style="margin: 10px 0 0; font-size: 9px; color: #555;">(Powered by Sicasa POS)</p>
        </div>

    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); 
        }
    </script>
</body>
</html>