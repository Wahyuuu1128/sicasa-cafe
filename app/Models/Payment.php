<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'user_id', 'metode_pembayaran', 'status_bayar', 'waktu_pembayaran'];

    // Relasi: Pembayaran ini untuk satu pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Pembayaran ini diverifikasi oleh satu kasir (user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}