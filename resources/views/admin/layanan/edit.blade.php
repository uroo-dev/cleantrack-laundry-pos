@extends('layouts.admin')

@section('title', 'Edit Layanan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.layanan.index') }}">Layanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
    <div class="glass-card rounded-2xl p-8 soft-shadow">
        <h2 class="text-headline-sm mb-6">Edit Layanan</h2>
        <form method="POST" action="{{ route('admin.layanan.update', $layanan) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-label-sm block mb-1.5">Nama Layanan <span class="text-error">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $layanan->nama) }}" required
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition {{ $errors->has('nama') ? 'border-error' : '' }}">
                    @error('nama') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-label-sm block mb-1.5">Harga per Kg <span class="text-error">*</span></label>
                    <input type="number" name="harga_perkg" value="{{ old('harga_perkg', $layanan->harga_perkg) }}" required
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition {{ $errors->has('harga_perkg') ? 'border-error' : '' }}">
                    @error('harga_perkg') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-label-sm block mb-1.5">Estimasi (Hari) <span class="text-error">*</span></label>
                    <input type="number" name="estimasi_hari" value="{{ old('estimasi_hari', $layanan->estimasi_hari) }}" required
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition {{ $errors->has('estimasi_hari') ? 'border-error' : '' }}">
                    @error('estimasi_hari') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $layanan->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-outline-variant/40 text-primary focus:ring-primary/30">
                        <span class="text-label-sm">Aktif</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="text-label-sm block mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                        class="bg-surface-container rounded-xl border border-outline-variant/40 px-4 py-2.5 w-full text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition {{ $errors->has('deskripsi') ? 'border-error' : '' }}">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="submit"
                    class="bg-primary text-on-primary px-6 py-2.5 rounded-xl font-bold text-label-sm hover:opacity-90 transition">Update</button>
                <a href="{{ route('admin.layanan.index') }}"
                    class="bg-surface-container text-on-surface px-6 py-2.5 rounded-xl font-bold text-label-sm hover:bg-surface-container-high transition">Batal</a>
            </div>
        </form>
    </div>
@endsection
