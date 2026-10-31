<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Fungsi relasi ini yang akan menarik nama kategori untuk ditampilkan di tabel
    public function kategori()
    {
        // Mengarahkan relasi ke model Category menggunakan foreign key 'category_id'
        return $this->belongsTo(Category::class, 'category_id');
    }
}