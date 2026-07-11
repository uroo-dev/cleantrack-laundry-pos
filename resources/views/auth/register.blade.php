@extends('layouts.auth')

@section('title', 'Register')

@section('auth-content')
    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">Daftar</h2>
    <p class="intro-x text-slate-400 mt-2 text-center xl:text-left">Buat akun baru untuk memulai</p>

    @if($errors->any())
        <div class="intro-x alert alert-danger bg-danger/20 text-danger border-danger/20 mt-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="intro-x mt-8">
            <input type="text" name="name" value="{{ old('name') }}" class="intro-x login__input form-control py-3 px-4 border border-slate-300/60 block" placeholder="Nama Lengkap" required>
            <input type="email" name="email" value="{{ old('email') }}" class="intro-x login__input form-control py-3 px-4 border border-slate-300/60 block mt-4" placeholder="Email" required>
            <input type="text" name="telepon" value="{{ old('telepon') }}" class="intro-x login__input form-control py-3 px-4 border border-slate-300/60 block mt-4" placeholder="No. Telepon">
            <input type="password" name="password" class="intro-x login__input form-control py-3 px-4 border border-slate-300/60 block mt-4" placeholder="Password" required>
            <input type="password" name="password_confirmation" class="intro-x login__input form-control py-3 px-4 border border-slate-300/60 block mt-4" placeholder="Konfirmasi Password" required>
        </div>
        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
            <button type="submit" class="btn btn-primary py-3 px-6 w-full xl:w-64">Daftar</button>
        </div>
    </form>

    <div class="intro-x mt-5 text-slate-500 text-center xl:text-left">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-medium">Masuk</a>
    </div>
@endsection
