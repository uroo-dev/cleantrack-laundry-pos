@extends('layouts.admin')

@section('title', 'Edit Pelanggan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pelanggan.index') }}">Pelanggan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="glass-card rounded-2xl p-6 soft-shadow">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-outline-variant/20">
        <div class="w-10 h-10 rounded-xl bg-primary-container/40 flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">edit_note</span>
        </div>
        <div>
            <h2 class="text-headline-sm font-bold">Edit Pelanggan</h2>
            <p class="text-label-sm text-on-surface-variant">Perbarui data pelanggan</p>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.pelanggan.update', $pelanggan) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Kode Pelanggan</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">badge</span>
                    <input type="text" value="{{ $pelanggan->kode_pelanggan }}" readonly
                        class="w-full pl-10 pr-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm opacity-60 cursor-not-allowed">
                </div>
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">
                    Nama Lengkap <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">person</span>
                    <input type="text" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required
                        class="w-full pl-10 pr-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all {{ $errors->has('nama') ? 'border-error' : '' }}">
                </div>
                @error('nama') <p class="text-label-sm text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">No. Telepon <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">phone</span>
                    <input type="text" name="telepon" value="{{ old('telepon', $pelanggan->telepon) }}" required
                        class="w-full pl-10 pr-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all {{ $errors->has('telepon') ? 'border-error' : '' }}">
                </div>
                @error('telepon') <p class="text-label-sm text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-label-sm text-on-surface-variant mb-1 block">Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">mail</span>
                    <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all {{ $errors->has('email') ? 'border-error' : '' }}">
                </div>
                @error('email') <p class="text-label-sm text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="text-label-sm text-on-surface-variant mb-1 block">Alamat</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-on-surface-variant">location_on</span>
                    <textarea name="alamat" rows="3"
                        class="w-full pl-10 pr-4 py-2.5 bg-surface-container rounded-xl border border-outline-variant/40 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all {{ $errors->has('alamat') ? 'border-error' : '' }}">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                </div>
                @error('alamat') <p class="text-label-sm text-error mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-outline-variant/20 flex items-center gap-3">
            <button type="submit" class="bg-primary text-on-primary px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined">save</span> Update
            </button>
            <a href="{{ route('admin.pelanggan.index') }}" class="bg-surface-container-high text-on-surface px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined">close</span> Batal
            </a>
        </div>
    </form>
</div>
@endsection
