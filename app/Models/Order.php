<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi (mass assignment)
    protected $guarded = [];

    // 1. Relasi ke tabel order_details (Daftar item yang dibeli)
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    // 2. Relasi ke tabel tables (Meja untuk Dine-in)
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}