@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
@endsection

@section('content')
    <h2 class="intro-x text-2xl font-medium">Pengaturan Aplikasi</h2>

    @if(session('success'))
        <div class="intro-x alert alert-success bg-success/20 text-success border-success/20 mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="box p-8 mt-5 intro-x">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nama Aplikasi</label>
                    <input type="text" name="nama_app" class="form-control {{ $errors->has('nama_app') ? 'border-danger' : '' }}" value="{{ old('nama_app', $settings->nama_app ?? 'LaundryKu') }}">
                    @error('nama_app') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control {{ $errors->has('telepon') ? 'border-danger' : '' }}" value="{{ old('telepon', $settings->telepon ?? '') }}">
                    @error('telepon') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ old('email', $settings->email ?? '') }}">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">Harga per Kg (Default)</label>
                    <input type="number" name="harga_perkg" class="form-control {{ $errors->has('harga_perkg') ? 'border-danger' : '' }}" value="{{ old('harga_perkg', $settings->harga_perkg ?? 7000) }}">
                    @error('harga_perkg') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control {{ $errors->has('alamat') ? 'border-danger' : '' }}" rows="3">{{ old('alamat', $settings->alamat ?? '') }}</textarea>
                    @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
@endsection
