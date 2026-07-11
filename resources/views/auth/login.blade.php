@extends('layouts.auth')

@section('title', 'Login')

@section('auth-content')
    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">Masuk</h2>
    <p class="intro-x text-slate-400 mt-2 text-center xl:text-left">Masuk ke akun Anda untuk mengelola laundry</p>

    @if($errors->any())
        <div class="intro-x alert alert-danger bg-danger/20 text-danger border-danger/20 mt-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="intro-x alert alert-success bg-success/20 text-success border-success/20 mt-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="intro-x mt-8">
            <input type="email" name="email" value="{{ old('email') }}" class="intro-x login__input form-control py-3 px-4 border border-slate-300/60 block" placeholder="Email" required autofocus>
            <input type="password" name="password" class="intro-x login__input form-control py-3 px-4 border border-slate-300/60 block mt-4" placeholder="Password" required>
        </div>
        <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
            <div class="flex items-center mr-auto">
                <input type="checkbox" name="remember" id="remember" class="form-check-input border border-slate-400/70 mr-2">
                <label for="remember" class="cursor-pointer">Ingat Saya</label>
            </div>
            <a href="#">Lupa Password?</a>
        </div>
        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
            <button type="submit" class="btn btn-primary py-3 px-6 w-full xl:w-64">Masuk</button>
        </div>
    </form>

    <div class="intro-x mt-5 text-slate-500 text-center xl:text-left">
        Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-medium">Daftar</a>
    </div>
@endsection
