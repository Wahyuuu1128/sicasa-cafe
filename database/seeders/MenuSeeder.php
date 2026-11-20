<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menuData = [
            'Signature Drink' => [
                ['nama_menu' => 'Kopi Susu Sicasa - Original', 'harga' => 18000],
                ['nama_menu' => 'Double Shoot Delight', 'harga' => 22000],
                ['nama_menu' => 'ButterScotch Coffee', 'harga' => 20000],
                ['nama_menu' => 'Peach Coffee', 'harga' => 18000],
                ['nama_menu' => 'Frappuccino', 'harga' => 25000],
                ['nama_menu' => 'Salted Caramel Macchiato', 'harga' => 25000],
                ['nama_menu' => 'Iced Cold Brew', 'harga' => 18000],
            ],
            'Espresso Series' => [
                ['nama_menu' => 'Americano (Hot)', 'harga' => 18000],
                ['nama_menu' => 'Americano (Cold)', 'harga' => 18000],
                ['nama_menu' => 'Caffè Latte (Hot)', 'harga' => 18000],
                ['nama_menu' => 'Caffè Latte (Cold)', 'harga' => 18000],
                ['nama_menu' => 'Cappuccino (Hot)', 'harga' => 20000],
                ['nama_menu' => 'Cappuccino (Cold)', 'harga' => 20000],
                ['nama_menu' => 'Moccacino (Hot)', 'harga' => 22000],
                ['nama_menu' => 'Moccacino (Cold)', 'harga' => 22000],
                ['nama_menu' => 'Affogato', 'harga' => 23000],
                ['nama_menu' => 'Caffè Latte Vanilla', 'harga' => 22000],
                ['nama_menu' => 'Caffè Latte Hazelnut', 'harga' => 22000],
                ['nama_menu' => 'Caffè Latte Caramel', 'harga' => 22000],
            ],
            'Manual Brew' => [
                ['nama_menu' => 'Vietnam Drip', 'harga' => 16000],
                ['nama_menu' => 'V60', 'harga' => 20000],
                ['nama_menu' => 'Japanese', 'harga' => 20000],
                ['nama_menu' => 'Filter Coffee (Ask Barista)', 'harga' => 20000],
            ],
            'Tea Series' => [
                ['nama_menu' => 'Yellow Submarine', 'harga' => 18000],
                ['nama_menu' => 'Lychee Tea', 'harga' => 18000],
                ['nama_menu' => 'Peach Tea', 'harga' => 18000],
                ['nama_menu' => 'Lemon Tea', 'harga' => 16000],
                ['nama_menu' => 'Mint Tea', 'harga' => 16000],
                ['nama_menu' => 'Strawberry Tea', 'harga' => 18000],
            ],
            'Milk Series' => [
                ['nama_menu' => 'Vanilla Milk', 'harga' => 17000],
                ['nama_menu' => 'Banana Milk', 'harga' => 17000],
                ['nama_menu' => 'Chocolate Milk', 'harga' => 17000],
                ['nama_menu' => 'Matcha Latte', 'harga' => 22000],
                ['nama_menu' => 'Taro Milk', 'harga' => 17000],
            ],
            'Iced Blend' => [
                ['nama_menu' => 'Matcha Blend', 'harga' => 25000],
                ['nama_menu' => 'Red Velvet', 'harga' => 25000],
                ['nama_menu' => 'Cookies N Cream', 'harga' => 25000],
                ['nama_menu' => 'Sweet Banana', 'harga' => 25000],
                ['nama_menu' => 'Choco Banana', 'harga' => 25000],
                ['nama_menu' => 'Strawberry Smoothies', 'harga' => 25000],
                ['nama_menu' => 'Choco Blend', 'harga' => 25000],
            ],
            'Sparkling' => [
                ['nama_menu' => 'Mojito Mint', 'harga' => 18000],
                ['nama_menu' => 'Blu Blue', 'harga' => 18000],
                ['nama_menu' => 'Iced Lychee Sparkling', 'harga' => 18000],
                ['nama_menu' => 'Mojito Strawberry', 'harga' => 18000],
                ['nama_menu' => 'Lime Squash', 'harga' => 18000],
            ],
            'Makanan Utama' => [
                ['nama_menu' => 'Chicken Popcorn - Sambal Matah', 'harga' => 22000],
                ['nama_menu' => 'Chicken Popcorn - Lada Hitam', 'harga' => 22000],
                ['nama_menu' => 'Chicken Popcorn - Asam Manis', 'harga' => 22000],
                ['nama_menu' => 'Chicken Popcorn - Cabe Garam', 'harga' => 22000],
                ['nama_menu' => 'Cumi Goreng Tepung - Sambal Matah', 'harga' => 22000],
                ['nama_menu' => 'Cumi Goreng Tepung - Asam Manis', 'harga' => 22000],
                ['nama_menu' => 'Cumi Goreng Tepung - Lada Hitam', 'harga' => 22000],
            ],
            'Snack' => [
                ['nama_menu' => 'Kentang Goreng', 'harga' => 15000],
                ['nama_menu' => 'Nugget', 'harga' => 15000],
                ['nama_menu' => 'Sausage', 'harga' => 15000],
            ],
            'Lain-lain' => [
                ['nama_menu' => 'Mineral Water', 'harga' => 5000],
                ['nama_menu' => 'Extra Shot (Espresso)', 'harga' => 4000],
            ],
        ];

        foreach ($menuData as $categoryName => $menus) {
            $category = Category::firstOrCreate(['nama_kategori' => $categoryName]);

            foreach ($menus as $item) {
                Menu::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'nama_menu'   => $item['nama_menu'],
                    ],
                    [
                        'harga'        => $item['harga'],
                        'stok'         => 100,
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
