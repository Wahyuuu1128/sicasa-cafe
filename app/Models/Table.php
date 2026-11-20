<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['nomor_meja', 'status_meja', 'qr_code_url'];

    // Relasi: Satu meja punya banyak pesanan
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}