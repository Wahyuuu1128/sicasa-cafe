<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\OrderController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. LANDING PAGE
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// ==========================================
// 1.5. PENGATUR LALU LINTAS DASHBOARD
// ==========================================
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'owner') {
        return redirect()->route('owner.dashboard');
    } elseif (auth()->user()->role === 'kasir') {
        return redirect()->route('kasir.dashboard');
    }
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// ==========================================
// 2. PENGATURAN AKUN BAWAAN BREEZE
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// 3. AREA KASIR
// ==========================================
Route::prefix('kasir')->middleware(['auth', 'role:kasir'])->group(function () {
    
    // 3.1 Dashboard & Riwayat
    Route::get('/dashboard', [PosController::class, 'dashboard'])->name('kasir.dashboard');
    Route::get('/riwayat', [PosController::class, 'history'])->name('kasir.history');
    
    // 3.2 Pesanan Masuk (Dine-in)
    Route::get('/pesanan-masuk', [PosController::class, 'index'])->name('kasir.pesanan');
    Route::post('/verifikasi-pembayaran/{order_id}', [PosController::class, 'verifyPayment'])->name('kasir.verify');
    Route::get('/cetak-struk/{order_id}', [PosController::class, 'printStruk'])->name('kasir.print');
    
    // 3.3 Point of Sales (Mesin Kasir Take-away)
    Route::get('/pos', [PosController::class, 'takeaway'])->name('kasir.pos');
    // Route checkout Take-away (Nanti kita buat fungsinya)
    // Route::post('/pos/checkout', [PosController::class, 'checkoutTakeaway'])->name('kasir.pos.checkout');

    // 3.4 Manajemen Menu & Stok
    Route::get('/manajemen-menu', [MenuController::class, 'index'])->name('kasir.menu');
    Route::post('/manajemen-menu/store', [MenuController::class, 'store'])->name('kasir.menu.store');
    Route::post('/manajemen-menu/update/{id}', [MenuController::class, 'update'])->name('kasir.menu.update');
    Route::delete('/manajemen-menu/delete/{id}', [MenuController::class, 'destroy'])->name('kasir.menu.destroy');
    Route::post('/toggle-stok/{menu_id}', [PosController::class, 'toggleStok'])->name('kasir.toggleStok');

    // 3.5 Kelola Kategori
    Route::post('/manajemen-kategori/store', [MenuController::class, 'storeCategory'])->name('kasir.category.store');
    Route::delete('/manajemen-kategori/delete/{id}', [MenuController::class, 'destroyCategory'])->name('kasir.category.destroy');

    // 3.6 Profil Kasir
    Route::get('/profil', [ProfileController::class, 'editKasir'])->name('kasir.profile');
});

// ==========================================
// 4. AREA OWNER
// ==========================================
Route::prefix('owner')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/riwayat-transaksi', [DashboardController::class, 'history'])->name('owner.history');
});

// 3.3 Point of Sales (Mesin Kasir Take-away)
    Route::get('/pos', [PosController::class, 'takeaway'])->name('kasir.pos');
    // Hapus tanda komentar (//) pada baris di bawah ini:
    Route::post('/pos/checkout', [PosController::class, 'checkoutTakeaway'])->name('kasir.pos.checkout');

    // ==========================================
// 4. AREA OWNER
// ==========================================
Route::prefix('owner')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/riwayat-transaksi', [DashboardController::class, 'history'])->name('owner.history');
    
    // Tambahkan baris ini untuk Export Excel/CSV:
    Route::get('/export-laporan', [DashboardController::class, 'export'])->name('owner.export');
});

// ==========================================
// 5. AREA PELANGGAN (QR MENU)
// ==========================================
// Webhook Midtrans (Harus POST dan ditaruh di luar perlindungan CSRF)
Route::post('/midtrans/callback', [\App\Http\Controllers\CustomerController::class, 'callback']);

Route::prefix('qr')->group(function () {
    // 1. Scan QR (Pilih Meja & Isi Nama)
    Route::get('/meja/{table_id}', [\App\Http\Controllers\CustomerController::class, 'index'])->name('customer.index');
    Route::post('/meja/{table_id}/set-nama', [\App\Http\Controllers\CustomerController::class, 'setNama'])->name('customer.setNama');
    
    // 2. Katalog Menu & Checkout
    Route::get('/meja/{table_id}/menu', [\App\Http\Controllers\CustomerController::class, 'menu'])->name('customer.menu');
    Route::post('/meja/{table_id}/checkout', [\App\Http\Controllers\CustomerController::class, 'checkout'])->name('customer.checkout');
    
    // 3. Status Pesanan
    Route::get('/order/{order_id}', [\App\Http\Controllers\CustomerController::class, 'status'])->name('customer.status');
});

require __DIR__.'/auth.php';