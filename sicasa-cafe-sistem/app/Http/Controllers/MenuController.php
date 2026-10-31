<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category; // Pastikan model ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->latest()->get();
        $categories = Category::all(); 

        return view('kasir.menu', compact('menus', 'categories'));
    }

    // Fungsi Kompresi Aman (Anti Halaman Putih)
    private function uploadAndCompress($file)
    {
        // Jika file tidak valid (misal kebesaran dari batas server), tolak perlahan
        if (!$file->isValid()) {
            return null;
        }

        $fileName = time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
        $destinationPath = storage_path('app/public/menus');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Cek apakah PHP GD aktif untuk kompresi
        if (extension_loaded('gd')) {
            $sourceImage = $file->getPathName();
            $info = @getimagesize($sourceImage);

            if ($info) {
                if ($info['mime'] == 'image/jpeg') {
                    $image = @imagecreatefromjpeg($sourceImage);
                    if ($image) {
                        imagejpeg($image, $destinationPath . '/' . $fileName, 50); // Kompres 50%
                        imagedestroy($image);
                        return 'menus/' . $fileName;
                    }
                } elseif ($info['mime'] == 'image/png') {
                    $image = @imagecreatefrompng($sourceImage);
                    if ($image) {
                        imagepng($image, $destinationPath . '/' . $fileName, 5); // Kompres level 5
                        imagedestroy($image);
                        return 'menus/' . $fileName;
                    }
                }
            }
        }

        // Jalur aman jika GD gagal/tidak aktif (Biar gak blank putih)
        return $file->store('menus', 'public');
    }

    public function storeCategory(Request $request) 
    {
        Category::create(['nama_kategori' => $request->nama_kategori]);
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroyCategory($id) 
    {
        Category::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }

    public function store(Request $request) 
    {
        $data = [
            'nama_menu' => $request->nama_menu,
            'category_id' => $request->category_id, // Menggunakan category_id sesuai DB lu
            'harga' => $request->harga,
            'is_available' => true,
        ];

        if ($request->hasFile('foto')) {
            $path = $this->uploadAndCompress($request->file('foto'));
            if ($path) $data['foto'] = $path;
        }

        Menu::create($data);
        return redirect()->back()->with('success', 'Menu baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $data = [
            'nama_menu' => $request->nama_menu,
            'category_id' => $request->category_id, // Fix Error Update Kategori
            'harga' => $request->harga,
        ];

        if ($request->hasFile('foto')) {
            if ($menu->foto && Storage::exists('public/' . $menu->foto)) {
                Storage::delete('public/' . $menu->foto);
            }
            $path = $this->uploadAndCompress($request->file('foto'));
            if ($path) $data['foto'] = $path;
        }

        $menu->update($data);
        return redirect()->back()->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id) 
    {
        $menu = Menu::findOrFail($id);
        if ($menu->foto && Storage::exists('public/' . $menu->foto)) {
            Storage::delete('public/' . $menu->foto);
        }
        $menu->delete();
        return redirect()->back()->with('success', 'Menu berhasil dihapus!');
    }
}