@extends('layouts.auth')

@section('title', 'Register')

@section('auth-content')
    <div class="text-center mb-6">
        <span class="material-symbols-outlined text-5xl mb-2" style="color: #004ac6;">person_add</span>
        <h2 class="text-2xl font-bold" style="color: #191c1e;">Daftar</h2>
        <p class="text-sm mt-1" style="color: #6b7280;">Buat akun baru untuk memulai</p>
    </div>

    @if($errors->any())
        <div class="mb-4 p-3 rounded-xl" style="background: rgba(220, 38, 38, 0.08); border: 1px solid rgba(220, 38, 38, 0.2);">
            <ul class="text-sm" style="color: #dc2626;">
                @foreach($errors->all() as $error)
                    <li class="flex items-start gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="flex items-center gap-2 text-sm font-medium mb-1.5" style="color: #191c1e;">
                    <span class="material-symbols-outlined text-base" style="color: #004ac6;">person</span>
                    Nama Lengkap
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 rounded-xl border"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="Nama lengkap Anda" required>
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium mb-1.5" style="color: #191c1e;">
                    <span class="material-symbols-outlined text-base" style="color: #004ac6;">email</span>
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2.5 rounded-xl border"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="contoh@email.com" required>
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium mb-1.5" style="color: #191c1e;">
                    <span class="material-symbols-outlined text-base" style="color: #004ac6;">phone</span>
                    No. Telepon
                </label>
                <input type="text" name="telepon" value="{{ old('telepon') }}"
                    class="w-full px-4 py-2.5 rounded-xl border"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="08xxxxxxxxxx">
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium mb-1.5" style="color: #191c1e;">
                    <span class="material-symbols-outlined text-base" style="color: #004ac6;">lock</span>
                    Password
                </label>
                <input type="password" name="password"
                    class="w-full px-4 py-2.5 rounded-xl border"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="Minimal 8 karakter" required>
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium mb-1.5" style="color: #191c1e;">
                    <span class="material-symbols-outlined text-base" style="color: #004ac6;">lock</span>
                    Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-2.5 rounded-xl border"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="Ulangi password" required>
            </div>
        </div>

        <button type="submit"
            class="w-full py-3 rounded-xl font-bold text-sm mt-6 transition-all hover:brightness-110 active:scale-[0.98]"
            style="background: #004ac6; color: #ffffff;">
            <span class="material-symbols-outlined align-middle mr-1">person_add</span>
            Daftar
        </button>
    </form>

    <p class="text-center text-sm mt-6" style="color: #6b7280;">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold" style="color: #004ac6;">Masuk</a>
    </p>
@endsection
