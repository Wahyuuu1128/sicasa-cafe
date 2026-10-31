<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // Buka gembok biar semua kolom bisa diisi otomatis
    protected $guarded = [];

    // 1. Relasi balik ke tabel orders (INI YANG BIKIN ERROR TADI)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // 2. Relasi balik ke tabel menus
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}