@extends('layouts.admin')

@section('title', 'Tambah Pelanggan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pelanggan.index') }}">Pelanggan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
    <div class="box p-8 intro-x mt-4">
        <h2 class="text-lg font-medium mb-6">Tambah Pelanggan Baru</h2>
        <form method="POST" action="{{ route('admin.pelanggan.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kode Pelanggan</label>
                    <input type="text" name="kode" class="form-control" value="{{ 'PLG-' . date('Ymd') . '-XXX' }}" readonly>
                </div>
                <div>
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'border-danger' : '' }}" value="{{ old('nama') }}" required>
                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control {{ $errors->has('telepon') ? 'border-danger' : '' }}" value="{{ old('telepon') }}">
                    @error('telepon') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ old('email') }}">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control {{ $errors->has('alamat') ? 'border-danger' : '' }}" rows="3">{{ old('alamat') }}</textarea>
                    @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="mt-6 flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
