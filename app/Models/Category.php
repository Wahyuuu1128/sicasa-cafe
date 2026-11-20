<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nama_kategori'];

    // Relasi: Satu kategori punya banyak menu (cth: Makanan punya banyak jenis makanan)
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}