@extends('layouts.kasir')
@section('title', 'Profil Staf')

@section('content')
    <div class="mb-6">
        <h2 class="font-heading text-2xl font-bold text-ink mb-1">Pengaturan Akun</h2>
        <p class="text-sm text-text-secondary">Kelola informasi profil, email, dan kata sandi akun lu.</p>
    </div>

    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-lg flex items-center gap-3 shadow-sm w-full max-w-4xl">
            <svg class="text-green-600" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <p class="text-sm font-semibold text-green-800">Perubahan berhasil disimpan!</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl">
        
        <!-- Informasi Profil -->
        <div class="bg-surface rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="font-heading font-bold text-lg text-ink mb-4">Informasi Profil</h3>
            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Nama Pengguna</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1">
                </div>
                <button type="submit" class="bg-brand-blue text-white font-bold px-6 py-2.5 rounded-lg shadow-sm hover:bg-blue-800 transition-colors mt-2">Simpan Profil</button>
            </form>
        </div>

        <!-- Ubah Kata Sandi -->
        <div class="bg-surface rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="font-heading font-bold text-lg text-ink mb-4">Ubah Kata Sandi</h3>
            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Kata Sandi Baru</label>
                    <input type="password" name="password" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Konfirmasi Sandi Baru</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:border-brand-blue focus:ring-1">
                </div>
                <button type="submit" class="bg-brand-lime text-ink font-bold px-6 py-2.5 rounded-lg shadow-sm hover:bg-[#bbf011] transition-colors mt-2">Update Sandi</button>
            </form>
        </div>

    </div>
@endsection