<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 26; $i++) {
            Table::create([
                'nomor_meja' => 'Meja ' . str_pad($i, 2, '0', STR_PAD_LEFT), // Hasil: Meja 01, Meja 02...
                'status_meja' => 'kosong',
                // Nanti url ini yang di-generate jadi QR Code di meja
                'qr_code_url' => 'https://sicasa.cafe/order?meja=' . $i 
            ]);
        }
    }
}