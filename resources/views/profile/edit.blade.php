@extends('layouts.owner')
@section('title', 'Pengaturan Akun - Sicasa Analytics')
@section('content')
    <div class="mb-8">
        <h2 class="font-heading text-2xl md:text-3xl font-bold text-ink mb-1">Pengaturan Akun</h2>
        <p class="text-sm text-text-secondary">Kelola informasi profil, email, dan kata sandi akun lu.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        
        <div class="bg-surface rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="font-heading text-lg font-bold text-ink mb-4">Informasi Profil</h3>
            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">Nama Pengguna</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
                </div>
                <div class="pt-2 flex items-center gap-4">
                    <button type="submit" class="bg-brand-blue text-white font-semibold text-sm px-6 py-2 rounded-lg shadow-sm hover:bg-blue-800 transition-colors">Simpan Profil</button>
                    @if (session('status') === 'profile-updated')
                        <p class="text-sm text-green-600 font-medium">Berhasil disimpan.</p>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-surface rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="font-heading text-lg font-bold text-ink mb-4">Ubah Kata Sandi</h3>
            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">Kata Sandi Baru</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">Konfirmasi Sandi Baru</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
                </div>
                <div class="pt-2 flex items-center gap-4">
                    <button type="submit" class="bg-brand-lime text-ink font-bold text-sm px-6 py-2 rounded-lg shadow-sm hover:bg-[#bbf011] transition-colors">Update Sandi</button>
                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-green-600 font-medium">Sandi diperbarui.</p>
                    @endif
                </div>
            </form>
        </div>

    </div>
@endsection