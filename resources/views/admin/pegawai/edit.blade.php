@extends('layouts.admin')

@section('title', 'Edit Pegawai')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pegawai.index') }}">Pegawai</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
    <div class="box p-8 intro-x mt-4">
        <h2 class="text-lg font-medium mb-6">Edit Pegawai</h2>
        <form method="POST" action="{{ route('admin.pegawai.update', $pegawai) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'border-danger' : '' }}" value="{{ old('name', $pegawai->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ old('email', $pegawai->email) }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control {{ $errors->has('phone') ? 'border-danger' : '' }}" value="{{ old('phone', $pegawai->phone) }}">
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control">
                        <option value="staff" {{ old('role', $pegawai->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ old('role', $pegawai->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Password (biarkan kosong jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'border-danger' : '' }}">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="flex items-center">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $pegawai->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
