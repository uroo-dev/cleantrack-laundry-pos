@extends('layouts.admin')

@section('title', 'Edit Pegawai')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pegawai.index') }}">Pegawai</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
    <div class="glass-card rounded-2xl p-8 soft-shadow">
        <h2 class="text-headline-sm mb-6">Edit Pegawai</h2>
        <form method="POST" action="{{ route('admin.pegawai.update', $pegawai) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-label-sm block mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $pegawai->name) }}" required
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition {{ $errors->has('name') ? 'border-error' : '' }}">
                    @error('name') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-label-sm block mb-1.5">Email <span class="text-error">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" required
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition {{ $errors->has('email') ? 'border-error' : '' }}">
                    @error('email') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-label-sm block mb-1.5">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $pegawai->phone) }}"
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition {{ $errors->has('phone') ? 'border-error' : '' }}">
                    @error('phone') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-label-sm block mb-1.5">Role</label>
                    <select name="role"
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition">
                        <option value="staff" {{ old('role', $pegawai->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ old('role', $pegawai->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="text-label-sm block mb-1.5">Password (biarkan kosong jika tidak diubah)</label>
                    <input type="password" name="password"
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition {{ $errors->has('password') ? 'border-error' : '' }}">
                    @error('password') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-label-sm block mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition">
                </div>
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $pegawai->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-outline-variant/40 text-primary focus:ring-primary/30">
                        <span class="text-label-sm">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="submit"
                    class="bg-primary text-on-primary px-6 py-2.5 rounded-xl font-bold text-label-sm hover:opacity-90 transition">Update</button>
                <a href="{{ route('admin.pegawai.index') }}"
                    class="bg-surface-container text-on-surface px-6 py-2.5 rounded-xl font-bold text-label-sm hover:bg-surface-container-high transition">Batal</a>
            </div>
        </form>
    </div>
@endsection
