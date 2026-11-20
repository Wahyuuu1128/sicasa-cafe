<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class MenuController extends Controller
{
    // ==========================================
    // 1. TAMPILKAN HALAMAN MANAJEMEN MENU
    // ==========================================
    public function index()
    {
        $menus = Menu::with('kategori')->get();
        $categories = Category::all();
        
        return view('kasir.menu', compact('menus', 'categories'));
    }

    // ==========================================
    // 2. TAMBAH MENU BARU
    // ==========================================
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_menu'   => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga'       => 'required|numeric',
            'foto'        => 'nullable|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                return redirect()->back()->withInput()->with('error', 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.');
            }

            $destinationPath = public_path('storage/menus');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $ext;
            $file->move($destinationPath, $filename);
            $data['foto'] = 'menus/' . $filename;
        }

        $data['is_available'] = true;
        Menu::create($data);

        return redirect()->back()->with('success', 'Menu baru berhasil ditambahkan!');
    }

    // ==========================================
    // 3. EDIT MENU
    // ==========================================
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        
        $data = $request->validate([
            'nama_menu'   => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga'       => 'required|numeric',
            'foto'        => 'nullable|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                return redirect()->back()->withInput()->with('error', 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.');
            }

            // Hapus foto lama jika ada
            if ($menu->foto) {
                $oldPath = public_path('storage/' . $menu->foto);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $destinationPath = public_path('storage/menus');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $ext;
            $file->move($destinationPath, $filename);
            $data['foto'] = 'menus/' . $filename;
        } else {
            unset($data['foto']);
        }

        $menu->update($data);

        return redirect()->back()->with('success', 'Perubahan menu berhasil disimpan!');
    }

    // ==========================================
    // 4. HAPUS MENU (DENGAN PROTEKSI ERROR)
    // ==========================================
    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            // Hapus foto dari storage kalau ada
            if ($menu->foto) {
                $oldPath = public_path('storage/' . $menu->foto);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            
            $menu->delete();
            
            return redirect()->back()->with('success', 'Menu berhasil dihapus permanen!');

        } catch (QueryException $e) {
            if($e->getCode() == "23000") {
                return redirect()->back()->with('error', 'GAGAL: Menu ini tidak bisa dihapus karena sudah tercatat di riwayat transaksi. Silakan ubah statusnya menjadi "Habis".');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database saat menghapus menu.');
        }
    }

    // ==========================================
    // 5. TAMBAH KATEGORI
    // ==========================================
    public function storeCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        Category::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    // ==========================================
    // 6. HAPUS KATEGORI (DENGAN PROTEKSI ERROR)
    // ==========================================
    public function destroyCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            
            return redirect()->back()->with('success', 'Kategori berhasil dihapus permanen!');

        } catch (QueryException $e) {
            if($e->getCode() == "23000") {
                return redirect()->back()->with('error', 'GAGAL: Kategori tidak bisa dihapus karena masih dipakai oleh menu. Pindahkan atau hapus menu terkait lebih dulu.');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan pada database saat menghapus kategori.');
        }
    }
}