@extends('layouts.kasir')
@section('title', 'Manajemen Menu')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="font-heading text-2xl font-bold text-ink mb-1">Manajemen Menu</h2>
            <p class="text-sm text-text-secondary">Kelola daftar menu dan ketersediaan stok.</p>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button onclick="openModal('categoryModal')" class="flex-1 md:flex-none bg-surface border border-gray-300 text-ink px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                Kelola Kategori
            </button>
            <button onclick="openModal('menuModal')" class="flex-1 md:flex-none bg-brand-lime text-ink px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#bbf011] transition-colors shadow-sm">
                + Tambah Menu
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-lg flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="text-green-600" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-green-700 font-bold">&times;</button>
        </div>
    @endif

    <div class="bg-surface rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col h-[calc(100vh-140px)]">
        <div class="p-0 overflow-y-auto flex-1">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-bold text-text-secondary uppercase tracking-wider sticky top-0 z-10">
                        <th class="px-6 py-4">Item Menu</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-right">Harga</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg border border-gray-200 overflow-hidden flex-shrink-0 bg-white">
                                        @if($menu->foto)
                                            <img src="{{ asset('storage/'.$menu->foto) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="font-heading font-bold text-base text-ink">{{ $menu->nama_menu }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-text-secondary">{{ $menu->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-heading font-bold text-brand-blue text-lg">
                                Rp{{ number_format($menu->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('kasir.toggleStok', $menu->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold px-3 py-1.5 rounded-md uppercase transition-colors shadow-sm border {{ $menu->is_available ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                        {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="openEditModal('{{ $menu->id }}', '{{ addslashes($menu->nama_menu) }}', '{{ $menu->category_id }}', '{{ $menu->harga }}')" class="text-gray-400 hover:text-brand-blue p-2 rounded hover:bg-blue-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                    </button>
                                    <button type="button" onclick="confirmDeleteAlert('/kasir/manajemen-menu/delete/{{ $menu->id }}', 'Menu')" class="text-gray-400 hover:text-red-600 p-2 rounded hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-20 text-center text-gray-400">Katalog menu masih kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: KELOLA KATEGORI -->
    <div id="categoryModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
        <div class="bg-surface rounded-xl shadow-xl w-full max-w-sm flex flex-col max-h-[85vh]">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 class="font-heading font-bold text-lg text-ink">Kelola Kategori</h3>
                <button type="button" onclick="closeModal('categoryModal')" class="text-gray-400 hover:text-red-500">X</button>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <form action="{{ route('kasir.category.store') }}" method="POST" class="mb-6">
                    @csrf
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-2">Tambah Kategori Baru</label>
                    <div class="flex gap-2">
                        <input type="text" name="nama_kategori" required placeholder="Cth: Minuman" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                        <button type="submit" class="bg-ink text-white px-4 py-2 rounded-lg font-bold">+</button>
                    </div>
                </form>
                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-xs font-bold text-text-secondary uppercase mb-3">Daftar Kategori</h4>
                    <ul class="space-y-2">
                        @foreach($categories as $cat)
                            <li class="flex justify-between items-center bg-gray-50 px-3 py-2.5 rounded-lg border border-gray-200">
                                <span class="text-sm font-semibold">{{ $cat->nama_kategori }}</span>
                                <button type="button" onclick="confirmDeleteAlert('/kasir/manajemen-kategori/delete/{{ $cat->id }}', 'Kategori')" class="text-gray-400 hover:text-red-500 p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: TAMBAH MENU -->
    <div id="menuModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
        <div class="bg-surface rounded-xl shadow-xl w-full max-w-md">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 class="font-heading font-bold text-lg text-ink">Tambah Menu Baru</h3>
                <button type="button" onclick="closeModal('menuModal')" class="text-gray-400 hover:text-red-500">X</button>
            </div>
            <form action="{{ route('kasir.menu.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Nama Menu</label>
                    <input type="text" name="nama_menu" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Kategori</label>
                        <select name="category_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                            <option value="">Pilih...</option>
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Harga (Rp)</label>
                        <input type="number" name="harga" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Foto Menu (Auto Compress)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-sm">
                </div>
                <button type="submit" class="w-full bg-brand-lime text-ink font-bold py-3 rounded-lg shadow-sm hover:bg-[#bbf011]">Simpan Menu</button>
            </form>
        </div>
    </div>

    <!-- MODAL 3: EDIT MENU -->
    <div id="editModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
        <div class="bg-surface rounded-xl shadow-xl w-full max-w-md">
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h3 class="font-heading font-bold text-lg text-ink">Edit Menu</h3>
                <button type="button" onclick="closeModal('editModal')" class="text-gray-400 hover:text-red-500">X</button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Nama Menu</label>
                    <input type="text" id="edit_nama_menu" name="nama_menu" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Kategori</label>
                        <select id="edit_category_id" name="category_id" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Harga (Rp)</label>
                        <input type="number" id="edit_harga" name="harga" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Ganti Foto (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-sm">
                </div>
                <button type="submit" class="w-full bg-brand-blue text-white font-bold py-3 rounded-lg shadow-sm hover:bg-blue-800">Update Perubahan</button>
            </form>
        </div>
    </div>

    <!-- MODAL 4: CUSTOM DELETE ALERT -->
    <div id="deleteAlert" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
        <div class="bg-surface rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <h3 class="font-heading font-bold text-xl text-ink mb-2">Yakin Ingin Menghapus?</h3>
            <p class="text-sm text-text-secondary mb-6" id="deleteMsg">Data yang dihapus tidak bisa dikembalikan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteAlert')" class="flex-1 bg-white border border-gray-300 text-ink font-semibold py-2.5 rounded-lg">Batal</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white font-bold py-2.5 rounded-lg shadow-sm">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
        
        function openEditModal(id, nama, cat_id, harga) {
            document.getElementById('editForm').action = "/kasir/manajemen-menu/update/" + id;
            document.getElementById('edit_nama_menu').value = nama;
            document.getElementById('edit_category_id').value = cat_id;
            document.getElementById('edit_harga').value = harga;
            openModal('editModal');
        }

        function confirmDeleteAlert(actionUrl, itemName) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteMsg').innerText = "Apakah lu yakin mau menghapus data " + itemName + " ini selamanya?";
            openModal('deleteAlert');
        }
    </script>
@endsection