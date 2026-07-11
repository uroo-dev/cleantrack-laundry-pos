@extends('layouts.auth')

@section('title', 'Login')

@section('auth-content')
    <div class="text-center mb-6">
        <span class="material-symbols-outlined text-5xl mb-2" style="color: #004ac6;">login</span>
        <h2 class="text-2xl font-bold" style="color: #191c1e;">Masuk</h2>
        <p class="text-sm mt-1" style="color: #6b7280;">Masuk ke akun Anda untuk mengelola laundry</p>
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

    @if(session('status'))
        <div class="mb-4 p-3 rounded-xl" style="background: rgba(22, 163, 74, 0.08); border: 1px solid rgba(22, 163, 74, 0.2); color: #16a34a;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="flex items-center gap-2 text-sm font-medium mb-1.5" style="color: #191c1e;">
                    <span class="material-symbols-outlined text-base" style="color: #004ac6;">email</span>
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2.5 rounded-xl border"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="contoh@email.com" required autofocus>
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium mb-1.5" style="color: #191c1e;">
                    <span class="material-symbols-outlined text-base" style="color: #004ac6;">lock</span>
                    Password
                </label>
                <input type="password" name="password"
                    class="w-full px-4 py-2.5 rounded-xl border"
                    style="background: #eceef0; border-color: rgba(194, 199, 203, 0.4); color: #191c1e;"
                    placeholder="Masukkan password" required>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4 text-sm">
            <label class="flex items-center gap-2 cursor-pointer" style="color: #6b7280;">
                <input type="checkbox" name="remember" id="remember"
                    class="rounded border-gray-300"
                    style="accent-color: #004ac6;">
                Ingat Saya
            </label>
            <a href="#" class="font-medium" style="color: #004ac6;">Lupa Password?</a>
        </div>

        <button type="submit"
            class="w-full py-3 rounded-xl font-bold text-sm mt-6 transition-all hover:brightness-110 active:scale-[0.98]"
            style="background: #004ac6; color: #ffffff;">
            <span class="material-symbols-outlined align-middle mr-1">login</span>
            Masuk
        </button>
    </form>

    <p class="text-center text-sm mt-6" style="color: #6b7280;">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold" style="color: #004ac6;">Daftar</a>
    </p>
@endsection
