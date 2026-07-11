@extends('layouts.admin')

@section('title', 'Edit Layanan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.layanan.index') }}">Layanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
    <div class="box p-8 intro-x mt-4">
        <h2 class="text-lg font-medium mb-6">Edit Layanan</h2>
        <form method="POST" action="{{ route('admin.layanan.update', $layanan) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'border-danger' : '' }}" value="{{ old('nama', $layanan->nama) }}" required>
                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">Harga per Kg <span class="text-danger">*</span></label>
                    <input type="number" name="harga_perkg" class="form-control {{ $errors->has('harga_perkg') ? 'border-danger' : '' }}" value="{{ old('harga_perkg', $layanan->harga_perkg) }}" required>
                    @error('harga_perkg') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">Estimasi (Hari) <span class="text-danger">*</span></label>
                    <input type="number" name="estimasi_hari" class="form-control {{ $errors->has('estimasi_hari') ? 'border-danger' : '' }}" value="{{ old('estimasi_hari', $layanan->estimasi_hari) }}" required>
                    @error('estimasi_hari') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="flex items-center">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $layanan->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Aktif</label>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control {{ $errors->has('deskripsi') ? 'border-danger' : '' }}" rows="3">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                    @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="mt-6 flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
