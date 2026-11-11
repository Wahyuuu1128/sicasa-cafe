<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Bikin Kategori
        $kopi = Category::create(['nama_kategori' => 'Coffee']);
        $nonKopi = Category::create(['nama_kategori' => 'Non-Coffee']);
        $makanan = Category::create(['nama_kategori' => 'Main Course']);

        // Bikin Menu Kopi
        Menu::create(['category_id' => $kopi->id, 'nama_menu' => 'Americano', 'harga' => 15000, 'stok' => 50]);
        Menu::create(['category_id' => $kopi->id, 'nama_menu' => 'Cafe Latte', 'harga' => 20000, 'stok' => 50]);

        // Bikin Menu Non-Kopi
        Menu::create(['category_id' => $nonKopi->id, 'nama_menu' => 'Matcha Latte', 'harga' => 22000, 'stok' => 30]);

        // Bikin Menu Makanan
        Menu::create(['category_id' => $makanan->id, 'nama_menu' => 'Nasi Goreng Sicasa', 'harga' => 25000, 'stok' => 20]);
    }
}